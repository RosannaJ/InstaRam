<?php

	$isDataClean = true;
    // $name = $connection = $bio = $grade = $username = $password =  $license = $birthdate = "";
    $nameErr = $profileErr = $connectionErr = $bioErr = $gradeErr = $usernameErr =  "";
    $target_dir = "users/";
    $file = "userinfo.json";
    $target_file = "";
    $profilePic = "";
    $imageFileType = "";
    $userInfo = [];
    $allUsers = get_all_usernames();
    //$password = 

	// place in already inputted data 
    $phpArray = json_decode(file_get_contents("users/" . $_SESSION['username'] . "/userinfo.json"), true);
    $username = $phpArray["username"];
    $connection = $phpArray["connection"];
    $bio = $phpArray["bio"];
    $name = $phpArray["name"];
    $password = $phpArray["password"];
    $license = $phpArray["license"];
    $birthdate = $phpArray["birthdate"];
    if ($connection == "current"){
        $grade = $phpArray["grade"];
    }
    if ($phpArray["imageFileType"] != ""){
        $imageFileType = $phpArray["imageFileType"];
        $profilePic = "users/" . $_SESSION['username'] . "/pfp.$imageFileType";
    } 
    
    //include "editprofile.inc";

    // process form data if submitted from page 8
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["page"] == 8) {
        

        // check if username is valid and not already in use (can be its current username)
        if (empty($_POST["username"])) {
            $usernameErr = "Username is required";
            $isDataClean = false;
        } else if (!preg_match("/^[a-z0-9-' ]*$/", $_POST["username"]) || str_contains($_POST["username"], " ")) { 
            $usernameErr = "Only lowercase letters and numbers allowed";
            $isDataClean = false;
        } else if (username_exists($_POST["username"], $allUsers) && strcmp($_POST["username"], $_SESSION["username"]) != 0) {
            $usernameErr = "Sorry, this username is already in use.";
            $isDataClean = false;
        }

        echo "username:";
        var_dump($isDataClean);

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

        /*// check if file is valid
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
        }*/

     
        // save data if valid
        if ($isDataClean) {
            //$phpArray = array();
            $newSubmission = "";
            $dest = $target_dir . $_SESSION['username'] . "/". $file;
            $newUser = [];

            // create folders if they don't exist
            /*if (!is_dir($target_dir)) {
                mkdir($target_dir);
            }

            if (!is_dir($target_dir . $username . "/")) {
                mkdir($target_dir . $username . "/");
            }

            if ( !is_dir($target_dir . $username . "/" . "posts/")) {
                mkdir($target_dir . $username . "/" . "posts/");
            }*/

            // write form data to json file
            $newSubmission = [
                "UID" => $_SESSION["username"],
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
            
            // add current profile and write to file
            //$phpArray[] = $newSubmission;
            file_put_contents($dest, json_encode($newSubmission, JSON_PRETTY_PRINT));

            // upload profile pic
            //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $username . "/" . "pfp." .$imageFileType);
            
            // redirect to other page if form was submitted successfully
            $_GET['page'] = 3;

        } else {
            // temp
            echo "unsuccessful submission of edit profile form";
        }
    }

    include "editprofile.inc";
?>