
function displayGrade() {
	if (document.getElementById("current") && document.getElementById("current").checked) {
		for (var i = 0; i < document.getElementsByClassName("grade").length; i++) {
			document.getElementsByClassName("grade")[i].style.display = "inline";
		} // for
		
	} else {
		for (var i = 0; i < document.getElementsByClassName("grade").length; i++) {
			document.getElementsByClassName("grade")[i].style.display = "none";
		} // for
	} // else
	
} // displayGrade

function hashPassword() {
	let password1 = document.getElementById("password");
	let password2 = document.getElementById("password2");

	// hash password1
	password1.value = hash(password1.value);

	// if exists, hash password2 
	if (password2) {
		password2.value = hash(password2.value);
	}

	// tell browser form can be submitted
	return true;
}

function hash(textToHash) {
	let hash = 0;

	// ...hashing function goes here...
    
	return hash;
}

function fetchData(request, functionToCall) {
	fetch ("getData.php?" + request, {
		method: "GET",
	})
	.then(response => response.json())
	.then(data => functionToCall(data))
	.catch(err => console.log("error occurred " + err));
}

function nextImage(num) {
	let thumbnails = document.getElementById("thumbnails").children;
	let lightboxImage = document.getElementById("content");
	
	//console.log("thumbnails: " + thumbnails);
	
	for (let i = 0; i < thumbnails.length; i++) {
		
		// look for image with same uid as current lightbox image
		if (getUID(lightboxImage.src) == getUID(thumbnails[i].src)) {
			
			// return next image
			if (i + num < thumbnails.length && i + num >= 0) {
				
				fetchData("UID=" + getUID(thumbnails[i + num ].src), function(data) {
					updateContents(data);
					//console.log("changed to: " + lightboxImage.src);

					displayLightBox("profileimages/" + data.UID + "." + data.imageType);
				});
				
			} // if
		} // if
	} // for
}

function filterImages() {
	let filter = document.getElementById("connectionFilter").value;
	console.log(filter);
	
	let search = document.getElementById("searchBar").value;
	console.log("searching: " + search);
	
	fetchData("connection=" + filter + "&search=" + search, updateThumbnails);

}

function updateThumbnails(data) {
	// remove all existing children in thumbnails div
	while (thumbnails.firstChild) {
		thumbnails.removeChild(thumbnails.firstChild);
	}	
	
	console.log(data);
	
	// for every image, create a new image object and add to thumbnails div
	for (i in data){
		let img = new Image();
		//console.log(data[i].UID);
		img.src = "thumbnails/" + data[i].UID + "." + data[i].imageType;
		
		img.alt = data[i].description;
		img.className = "thumbnail";
		img.onclick = function() { 
			displayLightBox("profileimages/" + data[i].UID + "." + data[i].imageType);
		};
		thumbnails.appendChild(img);
	}
}

//----------LightBox---------------
function displayLightBox(imageFile) { // set alt as well?
	let image = new Image();
	let lightBoxImage = document.getElementById("content");
	let reqUID = 0;

	image.src = imageFile;
	
	// set boundary size to size of image
	image.onload = function() {
		let width = image.width;
		document.getElementById("boundaryBigImage").style.width = width + "px";
	}
	
	// set content
	lightBoxImage.src = "" + image.src;
	
	// show lightbox if not already visible
	if (isVisible("lightbox") == false) {
		changeVisibility("lightbox");
		changeVisibility("positionBigImage");
	}
	
	// get the name of the file without the entension and directory
	reqUID = getUID(imageFile);
	
	if (imageFile != "") {
		fetchData("UID=" + reqUID, updateContents);
	}
	
	// set download link
	document.getElementById("imageDownload").href = imageFile;
	
}

// updates content of lightbox caption
function updateContents(data) {
	let gradeText = (data.grade) ? "Grade: " + data.grade + "<br>": "";
	let connectionText = (data.connection === "currentStudent") ? "Student" : data.connection;
	
	// capitalize first letter of connectionText
	connectionText = connectionText.charAt(0).toUpperCase() + connectionText.slice(1);
	
    console.log(data);
	
	document.getElementById("caption").innerHTML = "Name: " + data.name + "<br>"
													+ "Connection: " + connectionText + "<br>" 
													+ gradeText
													+ "Description: " + data.text;
												
 }

// change the visibility of divId
function changeVisibility(divId) {
	let elem = document.getElementById(divId);

	// toggle between hidden/unhidden
	if (elem) {
		elem.className = (elem.className == "hidden") ? "unhidden" : "hidden";
	};
  
} // changeVisibility

function isVisible(divId) {
	return document.getElementById(divId).className == "unhidden";
}

// close the lightbox
function closeLightBox() {
	changeVisibility("lightbox");
	changeVisibility("positionBigImage");
} // closeLightBox

function getUID(fileName) {
	return fileName.split("/")[fileName.split("/").length - 1].split(".")[0];
}
//----------End of LightBox---------------