$isDataClean = true;
	$fileErr = "";
	$captionErr = ""; // not used currently
	$caption = "";
	
	// save data if valid
	 if ($isDataClean) {
	    	$phpArray = [];
			$newPost = [];
			$target_dir = "users/" . $_SESSION["userID"] . "/posts/";
			$dest = $target_dir . "posts.json";
			$identifierFileName = "postID.txt";
			$newUID = 0;
		
			
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