<?php
    session_start();
    include "functions.php";

    // process form data if submitted
    include "login.php";
    include "signup.php";
    include "post.php";

    // decide which page to show
    if (!array_key_exists("page", $_SESSION)) {
        $_SESSION["page"] = 1;
    }
    if (array_key_exists("page", $_GET)) {
        $_SESSION["page"] = $_GET["page"];
    }

    // reset everything
    if (array_key_exists("action", $_GET) && $_GET["action"] == "del") {

        // delete postID file
        if (file_exists("postID.txt")) {
            unlink("postID.txt");
        }

        // delete users folder
        delete_folder("users/");

        // log out
        unset($_SESSION["username"]);
    }

    // show page content
    include "header.inc";
    	
    if ($_SESSION["page"] == 1) {
        include "loginform.inc";
    } else if ($_SESSION["page"] == 2) {
        include "signupform.inc";
    } else if ($_SESSION["page"] == 3) {
        include "explore.php";
    } else if ($_SESSION["page"] == 4) {
        include "postform.inc";
    } else {
        echo "page does not exist";
    }

    include "footer.inc";

?>


