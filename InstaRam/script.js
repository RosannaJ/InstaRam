
// displays the grade selection if selecting student (signup page)
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

// hash password before sending to server when signing up
function signupHash() {
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
} // signupHash

// hash password before sending to server to authenticate for login
function loginHash() {
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
} // loginHash

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
} // sha256

// calls the function passed in after doing a fetch request with the request passed in
// method and body are optional parameters
function fetchData(request, functionToCall, method, body) {

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
} // fetchData

// displays the next/previous thumbnail in the lightbox
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
} // nextImage

// filters the profiles on the explore page
function filterProfiles() {
	let connection = document.getElementById("connectionFilter");
	let search = document.getElementById("searchBar");
	let grade = document.getElementById("gradeFilter");
	
	// check if on explore page
	if (!connection || !search || !grade) { return; }

	// disable grade selection if not selecting student
	if (connection.value === "current") {
		grade.disabled = false;
	} else {
		grade.value = "all";
		grade.disabled = true;
	} // else

	// get profiles that match filter conditions and update displayed profiles
	fetchData("connection=" + connection.value + "&search=" + search.value + "&grade=" + grade.value, updateProfileThumbnails);
} // filterProfiles

// edits the caption of the current post
function editCaption() {
	let UID = getUID(document.getElementById("content").src);

	// edit caption
	fetchData("action=editPost&UID=" + UID, function(data) {}, "post", new FormData(document.forms["changeCaption"]));
	
	// update displayed info
	setTimeout(function() {
		changeVisibility("editLightbox");
		updateLightboxContents();
		changeVisibility("lightbox");
	}, 100);
	
	return false;
} // editCaption
		
// display the edit page
function displayEdit(){	
	let caption = document.getElementById("caption").innerHTML.split("<br>")[0];

	toggleEdit();

	document.getElementById("postCaption").value = caption;	
} // displayEdit

// toggle visibility of editing lightbox and the displaying lightbox
function toggleEdit() {
	changeVisibility("editLightbox");
	changeVisibility("lightbox");
} // toggleEdit

// updates displayed profile thumbnails with data passed in
function updateProfileThumbnails(data) {
	let thumbnails = document.getElementById("thumbnails");

	// remove all existing children in thumbnails div
	while (thumbnails.firstChild) {
		thumbnails.removeChild(thumbnails.firstChild);
	} // while
	
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
} // updateProfileThumbnails

function displayLightBox(imageFile, uid) {
	let image = new Image();
	let lightBoxImage = document.getElementById("content");
	let lightBoxImage2 = document.getElementById("contentEdit");

	image.src = imageFile;
	
	// set boundary size to size of image
	image.onload = function() {
		let width = image.width;
		document.getElementById("boundaryBigImage").style.width = width + "px";
	}
	
	// set content
	lightBoxImage.src = image.src;
	lightBoxImage.alt = uid;

	if (lightBoxImage2) {
		lightBoxImage2.src = image.src;
	} // if
	
	//lightBoxImage2.alt = uid;
	
	// show lightbox if not already visible
	if (isVisible("lightbox") == false) {
		changeVisibility("lightbox");
		changeVisibility("positionBigImage");
	} // if
	
	// update caption and alt
	if (imageFile != "") {
		updateLightboxContents();
	} // if
	
} // displayLightBox


 // sets caption and alt for lightbox image using data passed in
 function updateLightboxContents() {
	let elem = document.getElementById("like");
	let UID = getUID(document.getElementById("content").src);
	let comments = document.getElementById("comments");

	// update post caption if lightbox is displaying post
	if (isPost(document.getElementById("content").src)) {

		// show like button
		fetchData("action=checkIfLiked&UID=" + UID, function(data) {
		
			// toggle icon
			if (data.isLiked === true) {
				elem.innerHTML = "favorite";
			} else {
				elem.innerHTML = "favorite_border";
			} // else
		});

		// update info about post
		fetchData("UID=" + UID, function(data) {
			let likeText = (Object.keys(data.likes).length == 1) ? "like" : "likes";
			let options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'};
			let date = (new Date(data.date)).toLocaleString("en-CA", options);


			document.getElementById("caption").innerHTML = data.caption + "<br>"
														+ Object.keys(data.likes).length + " " + likeText
														+ "<br>Posted on " + date;

			document.getElementById("content").alt = data.caption;

			// remove all existing comments
			while (comments.firstChild) {
				comments.removeChild(comments.firstChild);
			}
		
			// display comments
			if (data.comments) {
				
				// loop through comments (most recent to least recent)
				for (let i = Object.keys(data.comments).length - 1; i >= 0; i--) {
					
					// create comment
					let comment = document.createElement("div");
				
					comment.innerHTML = "<a id='linkToPage' href='?page=6&user=" + data.comments[i].user +	"'>" + data.comments[i].username + "</a>" + ": " + data.comments[i].text;
					comment.className = "comment";
					
					// create delete button if needed
					if (data.comments[i].shouldDisplay === true) {
						let deleteButton = document.createElement("button");
						deleteButton.innerHTML = "Delete";
						deleteButton.className = "deletecomment";
						deleteButton.onclick = function() {
							deleteComment(data.comments[i].UID)
						};
						comment.appendChild(deleteButton);
					} // if

					// add comment to page
					comments.appendChild(comment);
				} // for
			} // if
		});
	} // if
	
	// update profile caption if lightbox is displaying user
	else {
		UID = document.getElementById("content").alt;

		// update displayed profile info
		fetchData("user=" + UID, function(data) {
			let connection = data.connection == "current" ? "Student" : "Alumni";

			// update caption
			document.getElementById("caption").innerHTML = "Username: " + data.username + "<br>"
															+ "Name: " + data.name + "<br>"
															+ "Connection: " + connection;
			document.getElementById("content").alt = data.username;
		});
	} // else
	
 } // updateLightboxContents

// change the visibility of divId
function changeVisibility(divId) {
	let elem = document.getElementById(divId);

	// toggle between hidden/unhidden
	if (elem) {
		elem.className = (elem.className == "hidden") ? "unhidden" : "hidden";
	} // if
  
} // changeVisibility

// returns whether the element with the id passed in is visible
function isVisible(divId) {
	return document.getElementById(divId).className == "unhidden";
} // isVisible

// close the lightbox
function closeLightBox() {
	changeVisibility("lightbox");
	changeVisibility("positionBigImage");
} // closeLightBox

// get UID from the fileName passed in
function getUID(fileName) {

	if (!isPost(fileName)) {
		return fileName.split("/")[fileName.split("/").length - 2];
	} // if

	return fileName.split("/")[fileName.split("/").length - 1].split(".")[0];
} // getUID

// returns whether the file passed in is a post
function isPost(fileName) {
	return !isNaN(fileName.split("/")[fileName.split("/").length - 1].split(".")[0]);
}

// toggle liking a post
function toggleLike() {
	let elem = document.getElementById("like");
	let lightboxImage = document.getElementById("content");
	
	// toggle like
	fetchData("action=like&UID=" + getUID(lightboxImage.src), function(data) {});

	// update displayed info after some time
	setTimeout(updateLightboxContents, 100);
	
} // toggleLike

// add a comment to the currently displayed post
function addComment() {
	let UID = getUID(document.getElementById("content").src);

	// add comment
	fetchData("action=addComment&UID=" + UID, function(data) {}, "post", new FormData(document.forms["commentForm"]));

	// update displayed info after some time
	setTimeout(updateLightboxContents, 100);

	document.getElementById("text").value = "";

	// prevent submission of form
	return false;
} // addComment

// delete the comment with the commentUID passed in
function deleteComment(commentUID) {
	let imageUID = getUID(document.getElementById("content").src);

	// delete comment
	fetchData("action=deleteComment&UID=" + imageUID + "&commentUID=" + commentUID, function(data) {}, "get");

	// update displayed info after some time
	setTimeout(updateLightboxContents, 100);
} // deleteComment

// toggle friend status between logged in user and the user passed in
function toggleFriend(user) {
	fetchData("action=friend&user=" + user, function(data) {
		// update button text
		document.getElementById("friendRequest").value = data.message;
	});
} // toggleFriend

// hides the decline friend request button
function hideDeclineButton() {
	document.getElementById("decline").style.display = "none";
} // hideDeclineButton

// deletes the post that is currently displayed
function deletePost() {

	// delete post
	fetchData("action=deletePost&UID=" + getUID(document.getElementById("content").src), function (data) {});

	// reload page
	setTimeout(function() {
		window.location.reload();
	}, 100);
} // deletePost

// updates the unread amount displayed on the navbar
// and if on inbox page, updates dipslayed notifications
function updateNotifs() {
	let notifications = document.getElementById("notifications");

	// get notifications
	fetchData("action=notifs", function (data) {
		let unreadAmount = 0;
		
		// count number of unread notifications
		for (let i = 0; i < Object.keys(data).length; i++) {
			if (!data[i].read) {
				unreadAmount++;
			} // if
		} // if

		// update number of unread notifications
		document.getElementById("unreadAmount").innerHTML = (unreadAmount == 0) ? "" : unreadAmount;

		// check if on inbox page
		if (notifications) {

			// remove all existing notifications
			while (notifications.firstChild) {
				notifications.removeChild(notifications.firstChild);
			} // while
		
			// display "no notifs" message if their are notifs
			if (Object.keys(data).length == 0) {
				let text = document.createElement("p");
				text.innerHTML = "You have no notifications.";
				text.className = "noNotifs";
				notifications.appendChild(text);
			} // if

			// create and display notifications
			for (let i = Object.keys(data).length - 1; i >= 0; i--) {
				let notif = document.createElement("div");
				let checkbox = document.createElement("input");
				let link = document.createElement("a");
				let profilePic = document.createElement("img");
				let username = document.createElement("p");
				let text = document.createElement("p");
				let date = document.createElement("p");
				let deleteButton = document.createElement("button");
				let dateDiv = document.createElement("div");

				let options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric'};
				let dateText = (new Date(data[i].date)).toLocaleString("en-CA", options).split(", ");
			
				let readStatus = data[i].read ? "read" : "unread";
				
				// set class for notif div
				notif.className = "notifs " + readStatus;
				notif.onclick = () => {
					document.getElementById(data[i].UID).checked = !document.getElementById(data[i].UID).checked;
					logCheckbox(data[i].UID);
				}

				// create checkbox
				checkbox.type = "checkbox";
				checkbox.id = data[i].UID;
				checkbox.checked = notifs[data[i].UID];
				checkbox.onclick = function(e) {
					logCheckbox(data[i].UID);
					e.stopPropagation();
				}
				checkbox.className = "notifSelect";
				notif.appendChild(checkbox);

				// set profile pic
				if (data[i].pfpsrc !== "") {
					profilePic.src = "users/" + data[i].user + "/pfp." + data[i].pfpsrc;
				} else {
					profilePic.src = "images/defaultpfp.jpg";
				} // else
				
				profilePic.className = "notifPfp";

				// create username
				username.innerHTML = data[i].username;
				username.className = "notifUsername";

				// create link (wraps profile pic and username)
				link.className = "notifUser";
				link.href = "?page=6&user=" + data[i].user;
				link.onclick = e => {
					e.stopPropagation();
				}
				link.appendChild(profilePic);
				link.appendChild(username);
				notif.appendChild(link);
				

				// set text
				text.innerHTML = " " + data[i].message;
				text.className = "notifText";
				notif.appendChild(text);

				date.innerHTML = dateText[0] + "<br>" + dateText[1];
				date.className = "notifDate";
				dateDiv.className = "dateDivs";
				dateDiv.appendChild(date);

				// if notification is related to a post, display post image
				if (data[i].src) {
					let image = document.createElement("img");
					image.src = data[i].src;
					image.className = "notifPostImage";
					notif.appendChild(image);
				} // if

				notif.appendChild(dateDiv);

				// add notification to page
				notifications.appendChild(notif);
				
			} // for
		} // if
	});
} // updateNotifs

// check for notifications every few seconds
function initNotifs() {
	if (document.getElementById("unreadAmount")) {
		updateNotifs();
		setInterval(function() {
			updateNotifs();
		}, 5000);
	} // if
} // initNotifs

// returns the ids of selected checkboxes as a FormData object
function getSelectedNotifs() {
	let checkboxes = document.getElementsByClassName("notifSelect");
	let reqUIDs = new FormData();

	for (let i = 0; i < checkboxes.length; i++) {	
		if (checkboxes[i].checked) {
			reqUIDs.append(i, checkboxes[i].id);
		} // if
	} // for

	return reqUIDs;
} // getSelectedNotifs

// deletes or marks selected notifications as read/unread depending on parameter passed in
function editNotifs(action2) {
	fetchData("action=editNotifs&action2=" + action2, function (data) {}, "post", getSelectedNotifs());
	setTimeout(updateNotifs, 100);
} // editNotifs

// toggles the selection of all notifications
function toggleAllNotifs() {
	if (document.getElementById("notifications")) {
		let selectAll = document.getElementById("notifSelectAll");
		let checkboxes = document.getElementsByClassName("notifSelect");

		// set all checkboxes to be the same as "selectAll" checkbox
		for (let i = 0; i < checkboxes.length; i++) {
			checkboxes[i].checked = selectAll.checked;
			notifs[checkboxes[i].id] = checkboxes[i].checked;
		} // for
	} // if
} // toggleAllNotifs

// stores whether the checkbox with the id passed in is checked (in the notifs object)
function logCheckbox(id) {
	notifs[id] = document.getElementById(id).checked;
	console.log(notifs);
} // logCheckbox

let notifs = []; // stores which notification checkboxes are checked