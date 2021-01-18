<?php
	class User extends Curd{
		// Token generator
		private function tokenGenerator($username){
			$randString = uniqid($username, true);
			$randomBytes = bin2hex(random_bytes(16));
			return $randString . $randomBytes;
		}

		// Token authentication
		function authentication($id, $token){
			$user = $this->read("users", ["id" => $id]);
			$user = $user->fetch_assoc();
			if($user["token"] == $token){
				return true;
			} else {
				return false;
			}
		}

		// User registration
		function signup(){
			$result = $this->read("users", ["username" => $_POST["username"]]);
			if($result->num_rows !== 0){
				return ["error" => "User already exists!"];
			} else {
				if($_POST["password"] !== $_POST["re-password"]){
					return ["error" => "Tow password doesn't match!"];
				} else {
					unset($_POST["re-password"]);
					unset($_POST["signup-submit"]);
					$_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
					$_POST["varified"] = (string)rand(1111, 9999);
					$_POST["token"] = $this->tokenGenerator($_POST["username"]);
					$this->create("users", $_POST);
					$user = $this->read("users", ["username" => $_POST["username"]]);
					$user = $user->fetch_assoc();
					$user_id = $user["id"];
					unset($_POST);
					$this->create("profile", ["user_id" => $user_id]);
					return ["success" => "Account created successfully!"];
				}
			}
		}

		// User login
		function signin(){
			$result = $this->read("users", ["username" => $_POST["username"]]);
			if($result->num_rows == 0){
				return ["error" => "User doesn't exists!"];
			} else {
				$user = $result->fetch_assoc();
				if(!password_verify($_POST["password"], $user["password"])){
					return ["error" => "Password doesn't match!"];
				} else {
					if($user["varified"] != 0 && $_POST["varification_code"] == 0){
						return ["error" => "Please enter the varification code!"];
					} else {
						if($user["varified"] != $_POST["varification_code"]){
							return ["error" => "Please enter the varification code!"];
						}
						$user_info = $this->read("users", ["id" => $user["id"]]);
						$this->update("users", "id", $user["id"], ["varified" => 0]);
						$user_info = $user_info->fetch_assoc();
						return ["id" => $user_info["id"], "username" => $user_info["username"], "token" => $user_info["token"]];
						unset($_POST);
					}
				}
			}
		}

		function profile($id, $token){
			if($this->authentication($id, $token)){
				$user_info = $this->read("users", ["id" => $id]);
				$user_info = $user_info->fetch_assoc();

				$profile_info = $this->read("profile", ["user_id" => $id]);
				$profile_info = $profile_info->fetch_assoc();

				$images = $this->read("photos", ["user_id" => $_SESSION["id"]]);
				$images_array = [];

				while($row = $images->fetch_assoc()){
					array_push($images_array, $row["photo"]);
				}

				$data = [
					"username" => $user_info["username"],
					"email" => $user_info["email"],
					"first_name" => $profile_info["first_name"],
					"last_name" => $profile_info["last_name"],
					"profile_image" => $profile_info["image"],
					"images" => $images_array
				];
				return $data;
			} else {
				return ["error" => "Authentication error!"];
			}
		}

		// Updating user info
		function updateProfile($id, $action, $token){
			if($action == "update-name"){
				// Updating name
				if($this->authentication($id, $token)){
					$this->update("profile", "user_id", $id, ["first_name" => $_POST["firstName"], "last_name" => $_POST["lastName"]]);
					return ["firstName" => $_POST["firstName"], "lastName" => $_POST["lastName"]];
				} else {
					return ["error" => "Authentication error!"];
				}
			} else {
				// Updating profile picture
				if($this->authentication($id, $token)){
					$file = $_FILES["profie-image"];
					$name = $file["name"];
					$tmp_name = $file["tmp_name"];
					$size = $file["size"];
					$type = $file["type"];
					$error = $file["error"];

					$type = explode(".", $name)[1];
					$type = strtolower($type);

					$allowed = ["jpg", "png", "jpeg"];
					if(in_array($type, $allowed)){
						if($size > 5000000){
							return ["error" => "Image size should be less then 5 mb"];
						} else {
							if($error != 0){
								return ["error" => "Unknown error occured"];
							} else {
								$full_name = "profile_image_" . $_SESSION['username'] . "_" . $_SESSION['id'] . "." . $type;
								$direction = "../profile_images/" . $full_name;
								move_uploaded_file($tmp_name, $direction);
								$this->update("profile", "user_id", $_SESSION['id'], ["image" => $full_name]);
								// Done uploading...
							}
						}
					} else {
						return ["error" => "Image type should be JPG/PNG/JPEG"];
					}

					// file informations
					return [
						"name" => $name,
						"tmp_name" => $tmp_name,
						"size" => $size,
						"type" => $type,
						"error" => $error
					];
				} else {
					return ["error" => "Authentication error!"];
				}
			}
		}

		// Deleting user profile
		function deleteProfile(){

		}

		// Upload image
		function uploadImage($id, $token, $data){
			if($this->authentication($id, $token)){
				$return_data = [];
				foreach ($data as $file) {
					$name = $file["name"];
					$tmp_name = $file["tmp_name"];
					$size = $file["size"];
					$type = $file["type"];
					$error = $file["error"];

					$type = explode(".", $name)[1];
					$name = explode(".", $name)[0];
					$name = str_replace(" ", "-", $name);
					$type = strtolower($type);

					$allowed = ["jpg", "png", "jpeg"];
					if(in_array($type, $allowed)){
						if($size > 10000000){
							return ["error" => "Image size should be less then 5 mb"];
						} else {
							if($error != 0){
								return ["error" => "Unknown error occured"];
							} else {
								$randomstr = uniqid($name);
								$full_name = "image" . $randomstr . "." . $type;
								$direction = "../upload_images/" . $full_name;
								move_uploaded_file($tmp_name, $direction);
								$this->create("photos", ["user_id" => $_SESSION["id"], "photo" => $full_name]);
								array_push($return_data, $full_name);
							}
						}
					} else {
						return ["error" => "Image type should be JPG/PNG/JPEG"];
					}
				}
				return $return_data;
			} else {
				return ["error" => "Authentication error!"];
			}
		}

		// Delete photos
		function deletePhoto($id, $token, $data){
			if($this->authentication($id, $token)){
				$this->delete("photos", ["user_id" => $_SESSION["id"], "photo" => $data]);
				return ["user_id" => $_SESSION["id"], "photo" => $data];
			} else {
				return ["error" => "Authentication error!"];
			}
		}
	}
