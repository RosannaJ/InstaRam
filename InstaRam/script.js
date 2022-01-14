
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
	let form = document.forms["form"];
	let pw1 = document.getElementById("pw");
	let pw2 = document.getElementById("pw2");
	let password1 = "";
	let password2 = "";

	// store hashed password1
	password1 = document.createElement("input");
	password1.type = "hidden";
	password1.name = "password";
	password1.value = sha256(pw1.value);
	form.appendChild(password1);

	// store hashed password2
	password2 = document.createElement("input");
	password2.type = "hidden";
	password2.name = "password2";
	password2.value = sha256(pw2.value);
	form.appendChild(password2);
	
	// submit form
	form.submit();
}

function calcHash() {
	let form = document.forms["form"];
	let randNum = Math.random() * 200;
	let pw = document.getElementById("pw");
	let password1 = "";
	let salt = document.createElement("input");
	let salt2 = document.getElementById("rand");

	// store salt
	salt.type = "hidden";
	salt.name = "salt";
	salt.value = randNum;
	form.appendChild(salt);

	// store hashed password
	password = document.createElement("input");
	password.type = "hidden";
	password.name = "password";
	password.value = sha256(sha256(pw.value) + randNum + "" + salt2.value);
	form.appendChild(password);

	// submit form
	form.submit;
}

// source: https://stackoverflow.com/questions/59777670/how-can-i-hash-a-string-with-sha256-in-js
function sha256(ascii) {
    function rightRotate(value, amount) {
        return (value>>>amount) | (value<<(32 - amount));
    };
    
    var mathPow = Math.pow;
    var maxWord = mathPow(2, 32);
    var lengthProperty = 'length'
    var i, j; // Used as a counter across the whole file
    var result = ''

    var words = [];
    var asciiBitLength = ascii[lengthProperty]*8;
    
    //* caching results is optional - remove/add slash from front of this line to toggle
    // Initial hash value: first 32 bits of the fractional parts of the square roots of the first 8 primes
    // (we actually calculate the first 64, but extra values are just ignored)
    var hash = sha256.h = sha256.h || [];
    // Round constants: first 32 bits of the fractional parts of the cube roots of the first 64 primes
    var k = sha256.k = sha256.k || [];
    var primeCounter = k[lengthProperty];
    /*/
    var hash = [], k = [];
    var primeCounter = 0;
    //*/

    var isComposite = {};
    for (var candidate = 2; primeCounter < 64; candidate++) {
        if (!isComposite[candidate]) {
            for (i = 0; i < 313; i += candidate) {
                isComposite[i] = candidate;
            }
            hash[primeCounter] = (mathPow(candidate, .5)*maxWord)|0;
            k[primeCounter++] = (mathPow(candidate, 1/3)*maxWord)|0;
        }
    }
    
    ascii += '\x80' // Append bit for a char (plus zero padding)
    while (ascii[lengthProperty]%64 - 56) ascii += '\x00' // More zero padding
    for (i = 0; i < ascii[lengthProperty]; i++) {
        j = ascii.charCodeAt(i);
        if (j>>8) return; // ASCII check: only accept characters in range 0-255
        words[i>>2] |= j << ((3 - i)%4)*8;
    }
    words[words[lengthProperty]] = ((asciiBitLength/maxWord)|0);
    words[words[lengthProperty]] = (asciiBitLength)
    
    // process each chunk
    for (j = 0; j < words[lengthProperty];) {
        var w = words.slice(j, j += 16); // The message is expanded into 64 words as part of the iteration
        var oldHash = hash;
        // This is now the undefinedworking hash", often labelled as variables a...g
        // (we have to truncate as well, otherwise extra entries at the end accumulate
        hash = hash.slice(0, 8);
        
        for (i = 0; i < 64; i++) {
            var i2 = i + j;
            // Expand the message into 64 words
            // Used below if 
            var w15 = w[i - 15], w2 = w[i - 2];

            // Iterate
            var a = hash[0], e = hash[4];
            var temp1 = hash[7]
                + (rightRotate(e, 6) ^ rightRotate(e, 11) ^ rightRotate(e, 25)) // S1
                + ((e&hash[5])^((~e)&hash[6])) // ch
                + k[i]
                // Expand the message schedule if needed
                + (w[i] = (i < 16) ? w[i] : (
                        w[i - 16]
                        + (rightRotate(w15, 7) ^ rightRotate(w15, 18) ^ (w15>>>3)) // s0
                        + w[i - 7]
                        + (rightRotate(w2, 17) ^ rightRotate(w2, 19) ^ (w2>>>10)) // s1
                    )|0
                );
            // This is only used once, so *could* be moved below, but it only saves 4 bytes and makes things unreadble
            var temp2 = (rightRotate(a, 2) ^ rightRotate(a, 13) ^ rightRotate(a, 22)) // S0
                + ((a&hash[1])^(a&hash[2])^(hash[1]&hash[2])); // maj
            
            hash = [(temp1 + temp2)|0].concat(hash); // We don't bother trimming off the extra ones, they're harmless as long as we're truncating when we do the slice()
            hash[4] = (hash[4] + temp1)|0;
        }
        
        for (i = 0; i < 8; i++) {
            hash[i] = (hash[i] + oldHash[i])|0;
        }
    }
    
    for (i = 0; i < 8; i++) {
        for (j = 3; j + 1; j--) {
            var b = (hash[i]>>(j*8))&255;
            result += ((b < 16) ? 0 : '') + b.toString(16);
        }
    }
    return result;
}

// calls the function passed in after doing a fetch request with the request passed in
// method and body are optional parameters
function fetchData(request, functionToCall, method, body) { // delete?

	fetch ("getData.php?" + request, {
		method: method,
		body: body
	})
	.then(response => {
		if (response) {
			return response.json();
		} else {
			return response.text();
		}
	})
	.then(data => functionToCall(data))
	.catch(err => console.log("error occurred " + err));
}

function nextImage(dir) {
	let thumbnails = document.getElementById("thumbnails").children;
	let lightboxImage = document.getElementById("content");

	for (let i = 0; i < thumbnails.length; i++) {
		
		// look for image with same uid as current lightbox image
		if (getUID(lightboxImage.src) == thumbnails[i].id || (!isPost(lightboxImage.src) &&  lightboxImage.alt == thumbnails[i].id)) {
			
			// display next image
			if (i + dir < thumbnails.length && i + dir >= 0) {
				thumbnails[i + dir].click();
				break;
			} // if
		} // if
	} // for
}

function filterProfiles() {
	let connection = document.getElementById("connectionFilter");
	let search = document.getElementById("searchBar");
	let grade = document.getElementById("searchBar");
	
	if (!connection || !search || !grade) { return; }

	fetchData("connection=" + connection.value + "&search=" + search.value + "&grade=" + grade.value, updateProfileThumbnails);

}

// editCaption
function editCaption() {
	let UID = getUID(document.getElementById("content").src);

	fetchData("action=editPost&UID=" + UID, function(data) {}, "post", new FormData(document.forms["changeCaption"]));
}
		
// edit post (only caption)	
function displayEdit(imageFile){	
	let caption = document.getElementById("caption").innerHTML;

	displayLightBox(imageFile);	
	changeVisibility("editPage");
	document.getElementById("postCaption").innerHTML = caption;	
}

function currentCaption(){
	return document.getElementById("caption").innerHTML;
}

// updates displayed profile thumbnails with data passed in
function updateProfileThumbnails(data) {
	let thumbnails = document.getElementById("thumbnails");

	// remove all existing children in thumbnails div
	while (thumbnails.firstChild) {
		thumbnails.removeChild(thumbnails.firstChild);
	}	
	
	// for every image, create a new image object and add to thumbnails div
	for (let i in data) {

		let card = document.createElement("div");
		let img = document.createElement("img");
		let username = document.createElement("a");
		let name = document.createElement("p");

		// set profile pic
		img.className = "thumbnail";

		if (data[i].imageFileType !== "") {
			img.src = "users/" + data[i].UID + "/pfp." + data[i].imageFileType;
		} else {
			img.src = "images/defaultpfp.jpg";
		}
		
		img.alt = data[i].username;

		// set username (linked to profile page)
		username.href = "?page=6&user=" + data[i].UID;
		username.innerHTML = data[i].username;
		username.onclick = e => {

			// prevent display of lightbox
			e.stopPropagation();
		}

		// set name
		name.innerHTML = data[i].name;
		name.className = "caption";

		// set div
		card.className = "card";
		card.id = data[i].username;
		card.onclick = function() {
			displayLightBox(img.src, data[i].UID);
		};

		card.appendChild(img);
		card.appendChild(username);
		card.appendChild(name);

		thumbnails.appendChild(card);
	}
}

function displayLightBox(imageFile, uid) {
	let image = new Image();
	let lightBoxImage = document.getElementById("content");

	image.src = imageFile;
	
	// set boundary size to size of image
	image.onload = function() {
		let width = image.width;
		document.getElementById("boundaryBigImage").style.width = width + "px";
	}
	
	// set content
	lightBoxImage.src = image.src;
	lightBoxImage.alt = uid;
	
	// show lightbox if not already visible
	if (isVisible("lightbox") == false) {
		changeVisibility("lightbox");
		changeVisibility("positionBigImage");
	}
	
	// update caption and alt
	if (imageFile != "") {
		updatePostContents();
	}
	
	/*// set download link
	document.getElementById("imageDownload").href = imageFile;*/
}


 // sets caption and alt for lightbox image using data passed in
 function updatePostContents() { // rename?
	let elem = document.getElementById("like");
	let UID = getUID(document.getElementById("content").src);
	let comments = document.getElementById("comments");

	if (isPost(document.getElementById("content").src)) {
		// show like button
		fetchData("action=checkIfLiked&UID=" + UID, function(data) {
		
			// toggle icon
			if (data.isLiked === true) {
    			elem.innerHTML = "favorite";
			} else {
    			elem.innerHTML = "favorite_border";
			}
		});

		// update info about post
		fetchData("UID=" + UID, function(data) {

			document.getElementById("caption").innerHTML = data.caption + "<br>"
														+ Object.keys(data.likes).length + " likes" + "<br>"
														+ "liked by: " + data.likes;

			document.getElementById("content").alt = data.caption;

			// remove all existing comments
			while (comments.firstChild) {
				comments.removeChild(comments.firstChild);
			}
		
			// display comments
			if (data.comments) {
				for (let i = 0; i < Object.keys(data.comments).length; i++) {
					let comment = document.createElement("div");
					let deleteButton = document.createElement("button");
				
					comment.innerHTML = data.comments[i].username + ": " + data.comments[i].text;
					comment.class = "comment";

					deleteButton.innerHTML = "Delete";
					deleteButton.onclick = function() {
						deleteComment(data.comments[i].UID)
					};

					comment.appendChild(deleteButton);
					comments.appendChild(comment);
				}
			}
		});
	} else {
		UID = document.getElementById("content").alt;

		// update displayed profile info
		// fetch profile info
		fetchData("user=" + UID, function(data) {

			// update caption
			document.getElementById("caption").innerHTML = "Username: " + data.username + "<br>"
															+ "Name: " + data.name + "<br>"
															+ "Connection: " + data.connection;
			document.getElementById("content").alt = data.username;
		});
	}
	
	
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

	if (!isPost(fileName)) {
		return fileName.split("/")[fileName.split("/").length - 2];
	}

	return fileName.split("/")[fileName.split("/").length - 1].split(".")[0];
}

function isPost(fileName) {
	return !isNaN(fileName.split("/")[fileName.split("/").length - 1].split(".")[0]);
}

function toggleLike() {

	let elem = document.getElementById("like");
	let lightboxImage = document.getElementById("content");
    
	// toggle like
	fetchData("action=like&UID=" + getUID(lightboxImage.src), function(data) {});

	// update displayed info after some time
	setTimeout(function() {
		updatePostContents();
	}, 100);
	
}

function addComment() {
	let UID = getUID(document.getElementById("content").src);

	// add comment
	fetchData("action=addComment&UID=" + UID, function(data) {}, "post", new FormData(document.forms["commentForm"]));

	// update displayed info after some time
	setTimeout(function() {
		updatePostContents();
	}, 100);

	document.getElementById("text").value = "";

	// prevent submission of form
	return false;
}


function deleteComment(commentUID) {
	let imageUID = getUID(document.getElementById("content").src);

	// delete comment
	fetchData("action=deleteComment&UID=" + imageUID + "&commentUID=" + commentUID, function(data) {}, "get");

	// update displayed info after some time
	setTimeout(function() {
		updatePostContents();
	}, 100);
}

function toggleFriend(user) {
	fetchData("action=friend&user=" + user, function(data) {
		// update button text
		document.getElementById("friendRequest").value = data.message;
	});
}

function hideDeclineButton() {
	document.getElementById("decline").style.display = "none";
}

function deletePost() {
	fetchData("action=deletePost&UID=" + getUID(document.getElementById("content").src), function (data) {});
	//closeLightBox();
	window.location.reload();
}