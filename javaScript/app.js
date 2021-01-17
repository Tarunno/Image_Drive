// Signin | Signout | Signup
class Sign{
	// signin , signup and signout action
	async submit(form, action){
		var data = new FormData(form)
		data.append(action + "-submit", true)
		console.log(Array.from(data))
		var url = "includes/index.inc.php"
		var res = await fetch(url, {
			method: 'POST',
			body: data
		})
		var data = await res.json()
		return data
	}
}

// Function calls
try {
	signup()
	signin()
} catch(e) {
	console.log(e)
} finally {
	signout()
}


// Function definations - signup
async function signup(){
	var signUp = new Sign()
	var signupForm = document.querySelector(".signup")

	try {
		signupForm.addEventListener("submit", task)
	} catch (e) {
		console.log(e)
		console.log("Because you are in profile page")
	}

	async function task(e){
		e.preventDefault()
		var response = await signUp.submit(signupForm, "signup")

		if(Object.keys(response) == "error"){
			document.querySelector(".signin-msg").innerHTML = response.error
			document.querySelector(".signin-msg").style.color = "crimson"
		}
		else if(Object.keys(response) == "success"){
			document.querySelector(".signin-msg").innerHTML = response.success
			document.querySelector(".signin-msg").style.color = "green"
			signupForm.reset()
		}
	}
}

// Function definations - signin
function signin(){
	var signinForm = document.querySelector(".signin")
	var varificationCode = document.querySelector(".varification_code")
	var signIn = new Sign()

	try {
		signinForm.addEventListener("submit", task)
	} catch (e) {
		console.log(e)
		console.log("Because you are in profile page")
	}

	async function task(e){
		e.preventDefault()
		var response = await signIn.submit(signinForm, "signin")

		if(Object.keys(response) == "error"){
			if(response.error == "Please enter the varification code!"){
				varificationCode.style.display = "block"
				varificationCode.value = null
			}
			document.querySelector(".signin-msg").innerHTML = response.error
			document.querySelector(".signin-msg").style.color = "crimson"
		}
		else{
			document.querySelector(".signin-msg").innerHTML = "You logged in successfully!"
			document.querySelector(".signin-msg").style.color = "green"
			varificationCode.style.display = "none"
			document.querySelector(".logout-btn").innerHTML = `<button type="submit" name="logout"> Logout </button>`
			document.querySelector(".profile-btn").innerHTML = `<a href="profile.php"> <button> Profile </button> </a>`
			signinForm.reset()
		}
	}
}

// Function definations - signout
async function signout(){
	var logOut = new Sign()
	var logoutForm = document.querySelector(".logout-btn")
	logoutForm.addEventListener("submit", task)

	async function task(e){
		e.preventDefault();
		var response = await logOut.submit(logoutForm, "logout")
		if(Object.keys(response) == "success"){
			logoutForm.innerHTML = null
			try {
				document.querySelector(".signin-msg").innerHTML = response.success
				document.querySelector(".signin-msg").style.color = "green"
				document.querySelector(".profile-btn").innerHTML = null
			} catch (e) {
				console.log(e)
			} finally {
				console.log("You are in profile page")
				location.replace("http://localhost/CURD/index.php");
			}
		}
		else{
			document.querySelector(".signin-msg").innerHTML = response.error
			document.querySelector(".signin-msg").style.color = "crimson"
		}
	}
}
