<?php

    $isDataClean = true;
    $name = $license = $connection = $bio = $grade = $username = $birthdate = "";
    $nameErr = $profileErr = $licenseErr = $connectionErr = $bioErr = $gradeErr = $usernameErr = $birthdateErr = $passwordErr = $password2Err = "";
    $target_dir = "users/";
    $file = "userinfo.json";
    $target_file = "";
    $imageFileType = "";

    $allUsers = get_all_users($target_dir);

    // process form data if submitted from page 2
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["page"] == 2) {
        
        // check if username is valid and not already in use
        if (empty($_POST["username"])) {
            $usernameErr = "Name is required";
            $isDataClean = false;
        } else if (!preg_match("/^[a-zA-Z-' ]*$/", $_POST["username"])) {
            $usernameErr = "Only letters and white space allowed";
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
            $name = strtolower(clean_data($_POST["name"])); //maybe make all lowercase for consistency
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
            if (strcmp($imageFileType, "image/jpg") != 0 && strcmp($imageFileType, "image/png") != 0) {
                $isDataClean = false;
                $profileErr = "Only jpg or png files are allowed.";
            } else if ($_FILES["fileToUpload"]["size"] > 4000000) {
                $isDataClean = false;
                $profileErr = "Files must be 4MB and under.";
            }
            
            // set to actual file extension
            $imageFileType = explode("/", $imageFileType)[1];    
        }
        
        // check if birthdate is valid
        if (!array_key_exists("birthdate", $_POST)){
            $birthdateErr = "Birthdate is required";
            $isDataClean = false;
        } else {
            $birthdate = clean_data($_POST["birthdate"]);
        }

        // save data if valid
        if ($isDataClean){
            $phpArray = array();
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
                "imageFileType" => $imageFileType
            ];
            
            // add current profile and write to file
            $phpArray[] = $newSubmission;
            file_put_contents($dest, json_encode($phpArray, JSON_PRETTY_PRINT));

            // upload profile pic
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $username . "/" . "pfp" . "." .$imageFileType);

            // temp
            echo("submitted successfully");
            
            // redirect to other page if form was submitted successfully
            $_GET['page'] = 3;
            
            // temp
            echo "New Account Created:<br>";
            echo "<pre>";
            var_dump($phpArray);
            echo "</pre>";
        } else {
            // temp
            echo "unsuccessful submission of signup form";
        }
    }
?>		