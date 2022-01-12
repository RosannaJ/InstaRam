<?php
	
	$isDataClean = true;
	$fileErr = "";
	$captionErr = ""; // not used currently
	$caption = "";
	
	// process form data if submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION["page"] == 7) {

		// validate caption
	    $caption = clean_data($_POST["caption"]);

	    // check if file is valid ----copied from signup.php, should this be a function?-----
	    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["size"] > 0) {
	        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	        $imageFileType = "";
	 
	        // Check if image file is an actual image or fake image
	        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);//??
	        if ($check === false) {
	            $fileErr = "Valid file type is required"; 
	            $isDataClean = false;
	        }

	        // get image type
	        $imageFileType = mime_content_type($_FILES["fileToUpload"]["tmp_name"]);
	            
	        // check if filetype and size is valid
	        if (strcmp($imageFileType, "image/jpeg") != 0 && strcmp($imageFileType, "image/png") != 0) {
	            $isDataClean = false;
	            $fileErr = "Only jpg or png files are allowed.";
	        } else if ($_FILES["fileToUpload"]["size"] > 4000000) {
	            $isDataClean = false;
	            $fileErr = "File must be 4MB and under.";
	        }
	        
	        // set to actual file extension
	        $imageFileType = explode("/", $imageFileType)[1];    
	    } else {
	    	$fileErr = "A file must be uploaded.";
	    	$isDataClean = false;
	    }

		// save data if valid
	    if ($isDataClean) {
	    	$phpArray = [];
			$newPost = [];
			$target_dir = "users/" . $_SESSION['username'] . "/posts/";
			$dest = $target_dir . "posts.json";
			$identifierFileName = "postID.txt";
			$newUID = 0;
		
			// get next uid if file exists
			if (file_exists($identifierFileName)) {
				$newUID = file_get_contents($identifierFileName);
			}
			
			// update uid
			file_put_contents($identifierFileName, $newUID + 1);

			// create folders if they don't exist
			if (!is_dir($target_dir)) {
				mkdir($target_dir);
			}

			// write form data to json file
			$newPost = [
				"caption" => $caption,
				"UID" => $newUID,
				"imageFileType" => $imageFileType,
				"likes" => []
			];

			// store previous posts in array if there are any
			if (file_exists($dest)) {
				$phpArray = json_decode(file_get_contents($dest), true);
			}

			// add new post to array
			$phpArray[] = $newPost;

			// store array in file
			file_put_contents($dest, json_encode($phpArray, JSON_PRETTY_PRINT));

			// upload image
			if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . $newUID . "." . $imageFileType)) {
				echo "file upload error.";
			}

			// redirect to next page
			$_SESSION['page'] = 3;

			echo "Posts:<br>";
            echo "<pre>";
            var_dump($phpArray);
            echo "</pre>";
	    } else {
	    	echo "unsuccessful post";
	    }
	}

?>