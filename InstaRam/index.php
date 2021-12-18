<?php
    session_start();
    include "functions.php";

    // process form data if submitted
    include "login.php";
    include "signup.php";

    // decide which page to show
    if (!array_key_exists("page", $_SESSION)) {
        $_SESSION["page"] = 1;
    }
    if (array_key_exists("page", $_GET)) {
        $_SESSION["page"] = $_GET["page"];
    }

    // show page content
    include "header.inc";
    	
    if ($_SESSION["page"] == 1) {
        echo "page 1";
        include "loginform.inc";
    } else if ($_SESSION["page"] == 2) {
        echo "page 2";
        include "signupform.inc";
    } else if ($_SESSION["page"] == 3) {
        echo "page 3";
        include "explore.php";
    } else {
        echo "page does not exist";
    }

    include "footer.inc";

?>


