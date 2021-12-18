<?php

    $isDataClean = true;
    $loginUsername = "";
    $loginUsernameErr = "";
    $loginPasswordErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["page"] == 1) {

        // check if username is valid
        if (empty($_POST["username"])) {
            $loginUsernameErr = "Please enter a username.";
            $isDataClean = false;
        } else if (!preg_match("/^[a-zA-Z-' ]*$/", $_POST["username"])) {
            $loginUsernameErr = "Please enter a valid username.";
            $isDataClean = false;
        }

        // make username stay if was inputted
        $loginUsername = clean_data($_POST["username"]);

        // check if username is valid
        if (empty($_POST["password"])) {
            $loginPasswordErr = "Please enter a password.";
            $isDataClean = false;
        } //else if (!preg_match("/^[a-zA-Z-' ]*$/", $_POST["username"])) {
        //     $loginPasswordErr = "Please enter a valid username.";
        //     $isDataClean = false;
        // }

        // TODO: check if username and password match

        // log in
        if ($isDataClean) {
            $_SESSION['username'] = $loginUsername;
            echo "logged in";
        } else {
            echo "log in failed";
        }
    }

?>