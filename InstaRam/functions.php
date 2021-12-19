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

    // returns all usernames by going through each folder
    function username_exists($username, $allUsers) {
        echo "Usernames: <br>";
        echo "<pre>";
        var_dump($allUsers);
        echo "</pre>";

        for ($i = 0; $i < count($allUsers); $i++) {
            if ($allUsers[$i] === $username) {
                return true;
            }
        }
        return false;
    }

    function get_all_users($fileName) {
        $users = [];

        // get the name of each user's folder
        if (file_exists($fileName)) {
            if (is_dir($fileName)) {
                $dh = opendir($fileName);
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
?>