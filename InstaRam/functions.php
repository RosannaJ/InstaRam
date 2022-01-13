<?php
    function clean_data($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }

    // checks if the username passed in exists in the array passed in
    function username_exists($username) {
        foreach (get_all_user_data() as $user) {
            if ($user["username"] === $username) {
                return true;
            }
        }
        return false;
    }

    // returns contents of all userinfo.json files as a single array
    function get_all_user_data() {
        $users = [];

        $folderName = "users/";

        // open users folder
        if (file_exists($folderName)) {
            if (is_dir($folderName)) {
                $dh = opendir($folderName);

                // go through each user's folder
                while (($file = readdir($dh)) !== false) {

                    // put user's info into array
                    if (!is_dir($file)) {
                        $dest = $folderName . $file . "/userinfo.json";
                        $users[] = json_decode(file_get_contents($dest), true);
                    }
                }
                closedir($dh);
            }
        }

        return $users;
    }

    // returns all posts under each user id
    function get_all_posts() {
        $posts = [];
        $folderName = "users/";

        // go through users folder
        if (file_exists($folderName)) {
            if (is_dir($folderName)) {
                $dh = opendir($folderName);

                // go through each user's folder
                while (($file = readdir($dh)) !== false) {
                    if (!is_dir($file)) {
                        $dest = $folderName . $file . "/posts/posts.json";

                        // put json from file into array
                        if (file_exists($dest)) {
                            $posts[$file] = json_decode(file_get_contents($dest), true);
                        }
                        
                    }
                }
                closedir($dh);
            }
        }

        return $posts;
    }

    // returns all posts not organized by user
    function get_posts_separated($users) {
        $posts = [];

        // loop through all user folders
        foreach ($users as $user) {
            $dest = "users/" . $user . "/posts/posts.json";

            // check if posts json file exists
            if (is_file($dest)) {

                // add user's posts to array
                $userPosts = json_decode(file_get_contents($dest), true);
                $posts = array_merge($posts, $userPosts);
            }
        }

        return $posts;
    }

    // returns id of person that made the post with the requested UID
    function get_user_of_post($UID) {
        $folderName = "users/";

        // go through users folder
        if (file_exists($folderName)) {
            if (is_dir($folderName)) {
                $dh = opendir($folderName);

                // go through each user's folder
                while (($file = readdir($dh)) !== false) {
                    if (!is_dir($file)) {
                        $dest = $folderName . $file . "/posts/posts.json";

                        // get their posts as an array
                        if (!is_dir($dest) && file_exists($dest)) {
                            $posts = json_decode(file_get_contents($dest), true);

                            // go through all posts and return id if uid is matching
                            foreach ($posts as $post) {
                                if ($post["UID"] == $UID) {
                                    return $file;
                                } // if
                            } // foreach
                        } // if
                    } // if
                } // while
                closedir($dh);
            } // if
        } // if

        return -1;
    } // get_user_of_post

    // compares the parameters passed in by UID
    // returns -1, 0, or 1
    // sorting with this function will result in an order of greatest to least
    function cmp_UID($a, $b) {
        if ($a["UID"] == $b["UID"]) {
            return 0;
        }

        return ($a["UID"] < $b["UID"]) ? 1 : -1;
    }

    // deletes the folder with the specified file path
    function delete_folder($folderName) {

        // open folder
        if (is_dir($folderName)) {
            $dh = opendir($folderName);
            while (($file = readdir($dh)) !== false) {
                $newFile = $folderName . $file;

                // delete files
                if (!is_dir($newFile . "/")) {
                    unlink($newFile);
                } 

                // delete folders
                else if ($file !== "." && $file !== ".."){
                    delete_folder($newFile . "/");
                }
            }
            closedir($dh);

            // delete root folder
            rmdir($folderName);
        }
    }

    function update_post($UID, $function) {
        // find user who posted the post with uid passed in
		$reqPostUser = get_user_of_post($UID);

		// get contents of file
		$dest = "users/" . $reqPostUser . "/posts/posts.json";
		$posts = json_decode(file_get_contents($dest), true);

		// loop through posts
		for ($i = 0; $i < count($posts); $i++) {

			// find post with requested UID
			if ($posts[$i]["UID"] == $UID) {
				$function($posts[$i]);
				break;
			}
		}

		// update posts.json file
		file_put_contents($dest, json_encode($posts, JSON_PRETTY_PRINT));
    }

    function log_in($username) { // needed?
        $_SESSION["userID"] = get_userID($username);
    }

    function get_user_data($user) {
        $userData = [];
        $dest = "users/" . $user . "/userinfo.json";

        if (is_file($dest)) {
            $userData = json_decode(file_get_contents($dest), true);
        }

        return $userData;
    }

    function update_user_data($user, $data) {
        $dest = "users/" . $user . "/userinfo.json";
        file_put_contents($dest, json_encode($data, JSON_PRETTY_PRINT));
    }

    // returns whether the specified index of the user's info contains the value passed in
    function contains($value, $user, $index) { // needed?
        $userData = get_user_data($user);
        return isset($userData[$index]) && in_array($value, $userData[$index]);
    }

    // returns username of user with the specified UID
    function get_username($userID) {
        return get_user_data($userID)["username"];
    }

    // returns UID of user with the specified username
    function get_userID($username) {
        foreach(get_all_user_data() as $user) {
            if ($user["username"] === $username) {
                return $user["UID"];
            }
        }
    }

?>