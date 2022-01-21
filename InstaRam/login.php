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
    } // if

    // if login form submitted on login page
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["page"] == 1) {
        $phpArray = [];
        $password = "";
        $userID = -1;
        
        // check if username is valid
        if (empty($_POST["username"])) {
            $loginUsernameErr = "Please enter a username.";
            $isDataClean = false;
        } else if (!preg_match("/^[a-zA-Z1-9-' ]*$/", $_POST["username"]) || str_contains($_POST["username"], " ")) {
            $loginUsernameErr = "Please enter a valid username.";
            $isDataClean = false;
        } else if (!username_exists($_POST["username"])) {
            $loginUsernameErr =  "Username does not exist.";
            $isDataClean = false;
        } // else if

        // make username stay in input field
        $loginUsername = clean_data($_POST["username"]);

        // get userID from username
        foreach (get_all_user_data() as $user) {
            if ($user["username"] === $loginUsername) {
                $userID = $user["UID"];
            } // if
        } // foreach

        // get user info
		if (file_exists("users/$userID/userinfo.json")) {
			$phpArray = json_decode(file_get_contents("users/$userID/userinfo.json"), true);
            $password = $phpArray["password"];
		} // if

        // check if username and password match
        if ($_POST["password"] !== hash("sha256", $password . $_POST["salt"] . $randNum)) {
            $loginPasswordErr = "Username or password is incorrect.";
            $isDataClean = false;
            $randNum = 0;
        } // if

        // log in
        if ($isDataClean) {
            log_in($loginUsername);
            $_GET['page'] = 4;
        } // if
    } // if

    // send randNum to client
    echo "<input type='hidden' id='rand' value='$randNum'>";

?>