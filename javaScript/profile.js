class Profile{
	constructor(){
		this.user = {
			username: null,
			email: null,
			firstName: null,
			lastName: null,
			image: null,
		},
		this.images = []
	}
	
	// fetching user information
	async getInfo(){
		var url = "includes/index.inc.php?user_info"
		var res = await fetch(url)
		var data = await res.json()
		return data
	}

	//updating fullname and profile image
	async update(form, action){
		if(action == "name-update"){
			// updating name
			var data = new FormData(form)
			data.append(action + "-submit", true)
			var url = "includes/index.inc.php"
			var res = await fetch(url, {
				method: 'POST',
				body: data
			})
			var data = await res.json()
			form.reset()
			return data
		}
		else{
			// updating profile picture
			var data = new FormData(form)
			data.append(action + "-submit", true)
			var url = "includes/index.inc.php"
			var res = await fetch(url, {
				method: 'POST',
				body: data
			})
			var data = await res.json()
			return data
		}
	}
}

profileInfo()

async function profileInfo(){
	var image_section = document.querySelector(".profile-info .image")
	var fullname_section = document.querySelector(".profile-info .full-name")
	var username_section = document.querySelector(".profile-info .username")
	var email_section = document.querySelector(".profile-info .email")
	var change_name_btn = document.querySelector("#change-name-btn")
	var change_image_btn = document.querySelector("#change-image-btn")

	var user = new Profile()
	var user_info = await user.getInfo()
	user.user = {
		username: user_info.username,
		email: user_info.email,
		firstName: user_info.first_name,
		lastName: user_info.last_name,
		image: user_info.profile_image
	}
	user.images = user_info.images

	if(user.user.image == null){
		image_section.style.background = `url('https://st2.depositphotos.com/1006318/5909/v/600/depositphotos_59094701-stock-illustration-businessman-profile-icon.jpg') no-repeat center center/cover`
	}
	else{
		image_section.style.background = `url('profile_images/${user.user.image}') no-repeat center center/cover`
	}

	username_section.innerHTML = `@${user.user.username}`
	email_section.innerHTML = `${user.user.email}`
	if(user.user.firstName == null){
		fullname_section.innerHTML = `Add full name `
	}
	else{
		fullname_section.innerHTML = `${user.user.firstName + " " + user.user.lastName} `
	}

	// Changing full name (first and last name)
	change_name_btn.addEventListener("click", function(e){
		var popUp = document.createElement("div")
		var innerPopUP = document.createElement("div")

		popUp.setAttribute("class", "pop-up-window")
		innerPopUP.setAttribute("class", "pop-up")

		innerPopUP.innerHTML = `<h3> Change full name </h3><br>
		<form class="name-update-form">
			<input type="text" name="firstName" placeholder="First name" required><br>
			<input type="text" name="lastName" placeholder="Last name" required><br>
			<button type="submit" name="update-name-submit"> Done </button>
		</form>`

		document.body.appendChild(popUp)
		document.body.appendChild(innerPopUP)

		updateName()

		popUp.addEventListener("click", () =>{
			document.body.removeChild(popUp)
			document.body.removeChild(innerPopUP)
		})
	})

	// Changing profile picture
	change_image_btn.addEventListener("click", function(e){
		var popUp = document.createElement("div")
		var innerPopUP = document.createElement("div")

		popUp.setAttribute("class", "pop-up-window")
		innerPopUP.setAttribute("class", "pop-up")

		innerPopUP.innerHTML = `<h3> Change profile image </h3><br>
		<p class="image-upload-error"> </p>
		<form class="image-update-form">
			<input type="file" name="profie-image" id="profile-image-field" required><br>
			<button type="submit" name="update-image-submit"> Upload </button>
		</form>`

		document.body.appendChild(popUp)
		document.body.appendChild(innerPopUP)

		updateImage()

		popUp.addEventListener("click", () =>{
			document.body.removeChild(popUp)
			document.body.removeChild(innerPopUP)
		})
	})
}

function updateName(){
	var image_section = document.querySelector(".profile-info .image")
	var fullname_section = document.querySelector(".profile-info .full-name")

	var profile = new Profile()
	var form = document.querySelector(".name-update-form")
	form.addEventListener("submit", update)
	async function update(e){
		e.preventDefault()
		var data = await profile.update(form, "name-update")
		if(Object.keys(data)[0] != 'error'){
			fullname_section.innerHTML = `${data.firstName + " " + data.lastName} `
		}
	}
}

function updateImage(){
	var image_section = document.querySelector(".profile-info .image")

	var profile = new Profile()
	var form = document.querySelector(".image-update-form")
	form.addEventListener("submit", update)
	async function update(e){
		e.preventDefault()
		var file = document.querySelector("#profile-image-field")
		var data = await profile.update(form, "image-update")
		if(Object.keys(data)[0] != 'error'){
			var reader = new FileReader()
			reader.readAsDataURL(file.files[0])
			reader.onload = ()=> {
				image_section.style.background = `url("${reader.result}") no-repeat center center/cover`
			}
		}
		else{
			document.querySelector(".image-upload-error").innerHTML = data.error
			document.querySelector(".image-upload-error").style.color = 'crimson'
			document.querySelector(".image-upload-error").style.fontSize = '11px'
			document.querySelector(".image-upload-error").style.fontWeight = "bold"
		}
		//console.log(data)
	}
}
