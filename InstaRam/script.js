
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
	let password1 = document.getElementById("password").value;	let password2 = document.getElementById("password2").value;
	let wholePass1 = "";
	let wholePass2 = "";

	password1 = hash(password1);
	password2 = hash(password2);
	

    //	random number randNumClient
	randNumClient = Math.floor(Math.random() * 200);

	// fetch randNum from server
	randNumServer = 10; // temp

	// hash entire password
	wholePass1 = hash(randNumClient + password1 + randNumServer);

	if (password2) { // ?
		wholePass2 = hash(randNumClient + password2 + randNumServer);
	}
	
	document.getElementById("password").value = wholePass1;

	if (password2) {
		document.getElementById("password2").value = wholePass2;
	}

	

	// tell browser form can be submitted
	return true;

	// store hash(password) on sign up
	// on log in, hash the entered password -> newlyEntered
	// compare stored hash(hash(password) + newRandNum + newRandNum2) with hash (newlyEntered + newRandNum + newRandNum2)


}

// should this be a php function
function compareLogIn(originalHash, enteredPassword) {
	let allHash1 = "";
	let allHash2 = "";
	let entered = enteredPassword;

	entered = hash(entered);

	//	random number randNumClient
	randNumClient = Math.floor(Math.random() * 200);

	// fetch randNum from server
	randNumServer = 10; // temp

	allHash1 = hash(randNumClient + originalHash + randNumServer);
	allHash2 = hash(randNumClient + entered + randNumServer);

}

// got function from https://stackoverflow.com/questions/6122571/simple-non-secure-hash-function-for-javascript
function hash(text) {
    var hash = 0;

	text = text.toString();

    if (text.length < 8) {
        return hash;
    }

    for (var i = 0; i < text.length; i++) {
        var char = text.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash; // Convert to 32bit integer
    }
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