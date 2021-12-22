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
    function username_exists($username, $allUsers) {
        for ($i = 0; $i < count($allUsers); $i++) {
            if ($allUsers[$i] === $username) {
                return true;
            }
        }
        return false;
    }

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

    function get_all_user_data() {
        $users = [];

        foreach (get_all_usernames() as $username) {
            $dest = "users/" . $username . "/userinfo.json";
            if (is_file($dest)) {
                $users[] = json_decode(file_get_contents($dest), true)[0];
            }
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
?>