<?php

	$isDataClean = true;
    $nameErr = $profileErr = $connectionErr = $bioErr = $gradeErr = $usernameErr =  "";
    $target_dir = "users/";
    $file = "userinfo.json";

    $username = "";
    $name = "";
    $connection = "";
    $bio = "";
    $grade = "";

    // process form data if submitted from page 8
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["page"] == 8) {
        

        // check if username is valid and not already in use (can be its current username)
        if (empty($_POST["username"])) {
            $usernameErr = "Username is required";
            $isDataClean = false;
        } else if (!preg_match("/^[a-z0-9-' ]*$/", $_POST["username"]) || str_contains($_POST["username"], " ")) { 
            $usernameErr = "Only lowercase letters and numbers allowed";
            $isDataClean = false;
        } else if (username_exists($_POST["username"]) && strcmp($_POST["username"], get_username($_SESSION["userID"])) != 0) {
            $usernameErr = "Sorry, this username is already in use.";
            $isDataClean = false;
        }

        // keep username in input field
        $username = clean_data($_POST["username"]);

        // check if name is valid
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
            $isDataClean = false;
        } else if (!preg_match("/^[a-zA-Z-' ]*$/", $_POST["name"])) {
            $nameErr = "Only letters and white space allowed";
            $isDataClean = false;
            $name = clean_data($_POST["name"]);
        } else {
            $name = clean_data($_POST["name"]); 
        }

        // check if connection is valid
        if (!array_key_exists("connection", $_POST)) {
            $connectionErr = "Connection is required";
            $isDataClean = false;
        } else {
            $connection = clean_data($_POST["connection"]);
        }

        // check if grade is valid
        if (array_key_exists("connection", $_POST) && $_POST["connection"] == "current") {
            if (empty($_POST["grade"])) {
                $gradeErr = "Grade is required";
                $isDataClean = false;
            } else {
                $grade = clean_data($_POST["grade"]);
            }
        }

        // validate bio
        $bio = clean_data($_POST["bio"]);
          
        // save data if valid
        if ($isDataClean) {
            $phpArray = array();
            $newSubmission = "";
            $dest = $target_dir . $_SESSION["userID"] . "/". $file;

            // get old user info
            $phpArray = json_decode(file_get_contents("users/" . $_SESSION["userID"] . "/userinfo.json"), true);

            // update user info
            $phpArray["username"] = $username;
            $phpArray["name"] = $name;
            $phpArray["connection"] = $connection;
            $phpArray["grade"] = $grade;
            $phpArray["bio"] = $bio;

            // put new user info in file
            file_put_contents($dest, json_encode($phpArray, JSON_PRETTY_PRINT));

            // redirect to other page if form was submitted successfully
            $_GET['page'] = 8;

        } else {
            // temp
            echo "unsuccessful submission of edit profile form";
        }
    }
?>