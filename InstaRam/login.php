<?php

    $isDataClean = true;
    $loginUsername = "";
    $loginUsernameErr = "";
    $loginPasswordErr = "";

    $target_dir = "users/";

    // this is also in signup.php, should it be moved?
    // $allProfilesFile = "allProfiles.json";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["page"] == 1) {
        $phpArray = [];
        $password = "";
        
        // check if username is valid
        if (empty($_POST["username"])) {
            $loginUsernameErr = "Please enter a username.";
            $isDataClean = false;
        } else if (!preg_match("/^[a-zA-Z1-9-' ]*$/", $_POST["username"]) || str_contains($_POST["username"], " ")) {
            $loginUsernameErr = "Please enter a valid username.";
            $isDataClean = false;
        } else if (!username_exists($_POST["username"], get_all_users($target_dir))) {
            $loginUsernameErr =  "Username does not exist.";
            $isDataClean = false;
        }

        // make username stay in input field
        $loginUsername = clean_data($_POST["username"]);

        // check if password is valid
        if (empty($_POST["password"])) {
            $loginPasswordErr = "Please enter a password.";
            $isDataClean = false;
        } 

        // store previous data in array if there is any
		if (file_exists("users/$loginUsername/userinfo.json")) {
			$phpArray = json_decode(file_get_contents($dest), true);
            $password = $phpArray["password"];
		}

        // TODO: check if username and password match
        // call compareLogIn($password, $_POST["password"]);

        // log in
        if ($isDataClean) {
            $_SESSION['username'] = $loginUsername;
            echo "logged in";
        } else {
            echo "log in failed";
        }
    }

?>