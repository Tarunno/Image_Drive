class DragDrop{
	constructor(input, filePad, view){
		this.input = input
		this.filePad = filePad
		this.view = view
		this.handleEvents()
	}
	handleEvents(){
		this.filePad.addEventListener("dragover", this.dragOver)
		this.filePad.addEventListener("dragleave", this.dragLeave)
		this.filePad.addEventListener("drop", this.dragDrop)
	}
	dragOver(e){
		e.preventDefault()
		this.style.background = "#c2c2c2"
	}
	dragLeave(e){
		this.style.background = "#dbdbdb"
	}
	async dragDrop(e){
		e.preventDefault()
		this.style.background = "#dbdbdb"

		var files = e.dataTransfer.files

		var formData = new FormData()
		for(var i=0; i<files.length; i++){
			formData.append("image"+i.toString(), files[i])
		}
		formData.append("image-upload", true)

		// console.log(Array.from(formData))

		var url = "includes/index.inc.php"
		var res = await fetch(url, {
			method: "POST",
			body: formData
		})
		var data = await res.json()
		// console.log(data)
		for(var image of data){
			view.innerHTML += `
			<div class="photo" style="background: url(${"upload_images/" + image}) no-repeat center center/cover">
				<div class="photo-overlay">
					<button id="view-photo-btn" data-image=${image}> View </button>
					<button id="delete-photo-btn" data-image=${image}> Delete </button>
				</div>
			</div>
			`
		}
	}
}

var inputField = document.querySelector(".mutiple-image-field")
var filePad = document.querySelector(".upload-section")
var view = document.querySelector(".photos")
var photos = document.querySelectorAll(".photo")

new DragDrop(inputField, filePad, view)
