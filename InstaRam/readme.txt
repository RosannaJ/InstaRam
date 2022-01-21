editprofile.inc
Form that allows the logged in user to edit their username, name, connection, and bio. 

editprofile.php
Processes and validates all the form elements of editprofile.inc. Once validated, the 
edited elements are saved to the user's userinfo.json.

explore.inc
Explore page that contains a search bar, which filters all displayed profiles by username, name, and bio, 
a connection filter, which filters alumni and students, a grade filter, and all the profiles on the website.
These profiles display the profile picture, username and name of each user.

//not being used 
explore.php

footer.inc
Closes the the body tag and HTML.

functions.php
Contains all the PHP functions that we created.

getData.php


header.inc
Header.inc begins the HTML page. It stores information about the page, the title, external fonts,
script.js, style.css and includes the navigation bar.

home.inc
Home page for the user. This page displays all the posts from the user and the user's friends from 
left to right in chronological order. These posts are displayed in thumbnails showing the username
of the poster, a thumbnail of the image, and the caption. Once a thumbnail is clicked on, it displays
the post in a lightbox that is interactive.

inbox.inc
Inbox page that displays all the notifications for a user. The user is able access the user who created 
the notification, the post that was interacted with if there was one, and what the user did. Notifications
are then deleteable and reloads every 5 seconds to get the newest notifications for the user.

index.php
Index.php determines what pages to show based on if the user is signed in and what page has been clicked 
on.

login.php
This page validates all the form elements of the login form. It determines if the entered username is valid
and the hashed password matches the stored hash that corresponds to the entered username. Once validated, 
the user is redirected to the explore page. 

loginform.inc
Form element that takes in the username and password of a user to sign in.

navbar.inc
Navigation bar that links to the explore page, home page, inbox page, create post, and profile page.

post.php
Processes the post form data. It validates the caption and file posted, and stores the information
about the post into the user's post folder. If it is the user's first post, it also creates the directory.

postEdit.inc
Creates a lightbox similar to the post for editing purposes. Form contains a box for editing the caption.

postform.inc
Form element used to create a post. Input fields include the file to be posted and the caption.

postLightbox.inc
Displays the post as a lightbox. The lightbox also contains a like button, arrows to move between posts,
a comment form, and the comments, which are displayed in a scrolling div.

profileLightbox.inc
Displays the user's profile in a lightbox. The profile picture is displayed, along with the username, 
name, and connection of the user.

profilepage.inc
The profile page retrieves the data for the currently logged in user, and displays the profile picture, name,
username, bio, and connection in their corresponding locations. The user can also edit his profile and 
log out. The user's posts are displayed in thumbnails in chronological order.

script.js
Script.js contains all our JavaScript functions.

signup.php
Processes and validates all the form elements of the sign up form. Once validated, the new user's 
information is stored in to the user's new userinfo.json. 

signupform.inc
Form element used to sign up. It takes in the user's name, username, profile picture, connection, bio,
birthday, license, and password. The password is required to be atleast 8 characters and confirmed by the 
user in another input field.

style.css
Contains all styling.

tos.inc
Contains all the terms and services of Ramstagram, which constitute an agree between the user and 
BigCypBigRosBigJosh, Inc.

