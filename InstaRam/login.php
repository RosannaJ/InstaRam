<?php

    $isDataClean = true;
    $loginUsername = "";
    $loginUsernameErr = "";
    $loginPasswordErr = "";
    $target_dir = "users/";
    $randNum = 0;

    // randomize randNum
    if ($randNum = 0) {
        $randNum = rand();
    }

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
        } else if (!username_exists($_POST["username"], get_all_usernames())) {
            $loginUsernameErr =  "Username does not exist.";
            $isDataClean = false;
        }

        // make username stay in input field
        $loginUsername = clean_data($_POST["username"]);

        // store previous data in array if there is any
		if (file_exists("users/$loginUsername/userinfo.json")) {
			$phpArray = json_decode(file_get_contents("users/$loginUsername/userinfo.json"), true);
            $password = $phpArray["password"];
		}

        // check if username and password match
        if ($_POST["password"] !== hash("sha256", $password . $_POST["salt"] . $randNum)) {
            $loginPasswordErr = "Username or password is incorrect.";
            $isDataClean = false;
            $randNum = 0;
        }

        // log in
        if ($isDataClean) {
            $_SESSION['username'] = $loginUsername;
            $_GET['page'] = 3;
            echo "logged in";
        } else {
            echo "log in failed";
        }
    }

    // send randNum to client
    echo "<input type='hidden' id='rand' value='$randNum'>";

?>