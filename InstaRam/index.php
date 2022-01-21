<?php

    session_start();

     // show header
    include "header.inc";
    include "functions.php";

    // process form data if submitted
    include "login.php";
    include "signup.php";
    include "post.php";

    // if logged in
    if (isset($_SESSION["userID"])) { 
        include "editprofile.php";
    } // if

    // reset everything
    if (array_key_exists("action", $_GET) && $_GET["action"] == "del") {

        // delete postID file
        if (file_exists("postID.txt")) {
            unlink("postID.txt");
        } // if

        // delete commentID file
        if (file_exists("commentUID.txt")) {
            unlink("commentUID.txt");
        } // if

        // delete identifier file
        if (file_exists("identifier.txt")) {
            unlink("identifier.txt");
        } // if

        // delete users folder
        delete_folder("users/");

        // log out
        unset($_SESSION["userID"]);
    }

    // log out
    if (array_key_exists("action", $_GET) && $_GET["action"] == "logout") {

        // log out
        unset($_SESSION["userID"]);
    } // if

    // decide which page to show
    if (!array_key_exists("page", $_SESSION)) {
        $_SESSION["page"] = 1;
    } // if
    if (array_key_exists("page", $_GET)) {
        $_SESSION["page"] = $_GET["page"];
    } // if

    if (!isset($_SESSION["userID"]) && $_SESSION["page"] != 2) {
        $_SESSION["page"] = 1;
    } // if

   
    // show page content
    if ($_SESSION["page"] == 1) {	
        include "loginform.inc";	
    } else if ($_SESSION["page"] == 2) {	
        include "signupform.inc";	
    } else if ($_SESSION["page"] == 3) {	
        include "home.inc";	
    } else if ($_SESSION["page"] == 4) {	
        include "explore.inc";	
    } else if ($_SESSION["page"] == 5) {	
        include "inbox.inc";	
    } else if ($_SESSION["page"] == 6) {	
        include "profilepage.inc";	
    } else if ($_SESSION["page"] == 7) { 	
        include "postform.inc";	
    } else if ($_SESSION["page"] == 8) {	
        include "editprofile.inc";	
    } else {	
        include "loginform.inc";	
    } // else

    include "footer.inc";

?>


