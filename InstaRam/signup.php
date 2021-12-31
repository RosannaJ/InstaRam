<?php

    $isDataClean = true;
    $name = $license = $connection = $bio = $grade = $username = $birthdate = $password = $password2 = "";
    $nameErr = $profileErr = $licenseErr = $connectionErr = $bioErr = $gradeErr = $usernameErr = $birthdateErr = $passwordErr = "";
    $target_dir = "users/";
    $file = "userinfo.json";
    $target_file = "";
    $imageFileType = "";

    $allUsers = get_all_usernames();

    // process form data if submitted from page 2
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["page"] == 2) {
        

        // check if username is valid and not already in use
        if (empty($_POST["username"])) {
            $usernameErr = "Username is required";
            $isDataClean = false;
        } else if (!preg_match("/^[a-z0-9-' ]*$/", $_POST["username"]) || str_contains($_POST["username"], " ")) { 
            $usernameErr = "Only lowercase letters and numbers allowed";
            $isDataClean = false;
        } else if (username_exists($_POST["username"], $allUsers)) {
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

        // check if license is valid
        if (!array_key_exists("license", $_POST)) {
            $licenseErr = "License is required";
            $isDataClean = false;
        } else {
            $license = clean_data($_POST["license"]);
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

        // check if file is valid
        if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["size"] > 0) {
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
     
            // Check if image file is an actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check === false) {
                $profileErr = "Valid file type is required";
                $isDataClean = false;
            }

            // get image type
            $imageFileType = mime_content_type($_FILES["fileToUpload"]["tmp_name"]);
                
            // check if filetype and size is valid
            if (strcmp($imageFileType, "image/png") !== 0 && strcmp($imageFileType, "image/jpeg") !== 0 || strcmp($imageFileType, "image/pjpeg") === 0) {
                $isDataClean = false;
                $profileErr = "Only jpg, jpeg, or png files are allowed.";
            } else if ($_FILES["fileToUpload"]["size"] > 4000000) {
                $isDataClean = false;
                $profileErr = "Files must be 4MB and under.";
            }
            
            // set to actual file extension
            $imageFileType = explode("/", $imageFileType)[1];    
        }
        
        // check if birthdate is valid
        if (!array_key_exists("birthdate", $_POST) || empty($_POST["birthdate"])){
            $birthdateErr = "Birthdate is required";
            $isDataClean = false;
        } else {
            $birthdate = $_POST["birthdate"];
        }

        // check if password is valid
        $tempPass = $_POST["password"];
        $tempPass2 = $_POST["password2"];
        if (strcmp($tempPass, $tempPass2) !== 0) {
            $passwordErr = "Passwords are not the same"; // change this to better message
            $isDataClean = false;
        } else {
            $password = $tempPass;
            $password2 = $tempPass2;
        }

        // save data if valid
        if ($isDataClean){
            //$phpArray = array();
            $newSubmission = "";
            $dest = $target_dir . $username . "/". $file;
            $newUser = [];

            // create folders if they don't exist
            if (!is_dir($target_dir)) {
                mkdir($target_dir);
            }

            if (!is_dir($target_dir . $username . "/")) {
                mkdir($target_dir . $username . "/");
            }

            if (!is_dir($target_dir . $username . "/" . "posts/")) {
                mkdir($target_dir . $username . "/" . "posts/");
            }

            // write form data to json file
            $newSubmission = [
                "username" => $username,
                "name" => $name,
                "license" => $license,
                "connection" => $connection,
                "grade" => $grade,
                "bio" => $bio,
                "birthdate" => $birthdate,
                "imageFileType" => $imageFileType,
                "password" => $password
            ];

            //$_SESSION['username'] = $username;
            log_in($username);
            
            // add current profile and write to file
            //$phpArray[] = $newSubmission;
            file_put_contents($dest, json_encode($newSubmission, JSON_PRETTY_PRINT));

            // upload profile pic
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $username . "/" . "pfp" . "." .$imageFileType);
            
            // redirect to other page if form was submitted successfully
            $_GET['page'] = 3;

        } else {
            // temp
            echo "unsuccessful submission of signup form";
        }
    }
?>		