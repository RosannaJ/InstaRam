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
    function username_exists($username, $allUsers) { //*** use in_array() instead?, // rename to user_exists?
        for ($i = 0; $i < count($allUsers); $i++) {
            if ($allUsers[$i] === $username) {
                return true;
            }
        }
        return false;
    }

    // returns an array of all existing usernames
    function get_all_usernames() {
        $folderName = "users/";
        $users = [];

        // get the name of each user's folder
        if (file_exists($folderName)) {
            if (is_dir($folderName)) {
                $dh = opendir($folderName);
                while (($file = readdir($dh)) !== false) {
                    if (!is_dir($file)) {
                        $users[] = $file;
                    }
                }
                closedir($dh);
            }
        }

        return $users;
    }

    // returns contents of all userinfo.json files as a single array
    function get_all_user_data() {
        $users = [];

        foreach (get_all_usernames() as $username) { // loop through all folders instead?
            /*$dest = "users/" . $username . "/userinfo.json";
            if (is_file($dest)) {
                $users[] = json_decode(file_get_contents($dest), true);
            }*/
            $users[] = get_user_data($username);
        }

        return $users;
    }

    // returns all posts under each username
    function get_all_posts() {
        $posts = [];

        foreach (get_all_usernames() as $username) {
            $dest = "users/" . $username . "/posts/posts.json";
            if (is_file($dest)) {
                $posts[$username] = json_decode(file_get_contents($dest), true);
            }
        }

        return $posts;
    }

    // return username of person that made the post with the requested UID
    function get_username_of_post($UID) {

        // loop through all posts.json files
        foreach (get_all_usernames() as $username) {
            $dest = "users/" . $username . "/posts/posts.json";
            if (is_file($dest)) {
                $posts = json_decode(file_get_contents($dest), true);

                // go through all posts and return username if uid is matching
                foreach ($posts as $post) {
                    if ($post["UID"] == $UID) {
                        return $username;
                    }
                }
            }
        }
    }

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
        // find post with uid
		$reqPostUsername = get_username_of_post($_GET["UID"]);
		
		// get contents of file
		$dest = "users/" . $reqPostUsername . "/posts/posts.json";
		$posts = json_decode(file_get_contents($dest), true);

		// loop through posts
		for ($i = 0; $i < count($posts); $i++) {

			// find post with requested UID
			if ($posts[$i]["UID"] == $_GET["UID"]) {
				$function($posts[$i]);
				break;
			}
		}

		// update posts.json file
		file_put_contents($dest, json_encode($posts, JSON_PRETTY_PRINT));
    }

    function log_in($username) { // needed?
        if (username_exists($username, get_all_usernames())) {
            $_SESSION["username"] = $username;
            return true;
        }

        return false;
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

?>