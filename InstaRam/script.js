
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

/*
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

	// send hash(password) and randNum to server/php
	// in php, calc and store hash(password + randNums)
	// on login, compare hash(hash(enteredPassword) + randNums) with storedPassword

}
*/
/*
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
*/
/*
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
*/

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
	
	for (let i = 0; i < thumbnails.length; i++) {
		
		// look for image with same uid as current lightbox image
		if (getUID(lightboxImage.src) == getUID(thumbnails[i].src)) {
			
			// return next image
			if (i + num < thumbnails.length && i + num >= 0) {
				
				fetchData("UID=" + getUID(thumbnails[i + num ].src), function(data) {
					updateContents(data);

					displayLightBox("profileimages/" + data.UID + "." + data.imageType);
				});
				
			} // if
		} // if
	} // for
}

function filterImages() {
	let filter = document.getElementById("connectionFilter").value;
	
	let search = document.getElementById("searchBar").value;
	
	fetchData("connection=" + filter + "&search=" + search, updateThumbnails);

}

function updateThumbnails(data) {
	// remove all existing children in thumbnails div
	while (thumbnails.firstChild) {
		thumbnails.removeChild(thumbnails.firstChild);
	}	
	
	// for every image, create a new image object and add to thumbnails div
	for (i in data){
		let img = new Image();
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