<?php
	// Class autoloader
	include "autoLoader.inc.php";

	// Session handling
	session_start();
	$user_id = "";
	$username = "";
	if(isset($_SESSION['id'])){
		$user_id = $_SESSION['id'];
		$username = $_SESSION['username'];
	}

	// Class declarations
	$curd = new Curd();
	$user = new User();

	// Connection error
	$conn_error = "";
	if(isset($_GET['error'])){
		$error = str_replace("_", " ", $_GET['error']);
		if($error == "Connection error"){
			$conn_error = $error;
		}
	}

	// Signup form submit | POST
	if(isset($_POST['signup-submit'])){
		$response = $user->signup();
		print_r(json_encode($response));
	}

	// Signin form submit | POST
	if(isset($_POST['signin-submit'])){
		$response = $user->signin();
		if(isset($response["username"])){
			if($user->authentication($response["id"], $response["token"])){
				$_SESSION["id"] = $response["id"];
				$_SESSION["username"] = $response["username"];
				$_SESSION["token"] = $response["token"];
			}
		}
		print_r(json_encode($response));
	}

	// Logout form submit | POST
	if(isset($_POST['logout-submit'])){
		if(isset($_SESSION["username"])){
			session_unset();
			session_destroy();
			$response = ["success" => "You logged out successfully!"];
		} else {
			$response = ["error" => "You are not logged in!"];
		}
		print_r(json_encode($response));
	}

	// Getting user info
	if(isset($_GET["user_info"])){
		$user_info = $user->profile($_SESSION["id"], $_SESSION["token"]);
		print_r(json_encode($user_info));
	}

	// Update user fullname
	if(isset($_POST["name-update-submit"])){
		$data = $user->updateProfile($_SESSION["id"], "update-name", $_SESSION["token"]);
		print_r(json_encode($data));
	}

	// Update user profile picture
	if(isset($_POST["image-update-submit"])){
		$data = $user->updateProfile($_SESSION["id"], "update-image", $_SESSION["token"]);
		print_r(json_encode($data));
	}

	// Upload photos
	if(isset($_POST["image-upload"])){
		$data = [];
		foreach ($_FILES as $file) {
			array_push($data, $file);
		}
		$data = $user->uploadImage($_SESSION["id"], $_SESSION["token"], $data);
		print_r(json_encode($data));
	}

	// Delete photos
	if(isset($_GET["delete-photo"])){
		$image = $_GET["delete-photo"];
		$res = $user->deletePhoto($_SESSION["id"], $_SESSION["token"], $image);
		print_r(json_encode($res));
	}
