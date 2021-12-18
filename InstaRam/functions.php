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
        echo "<pre>";
        var_dump($allUsers);
        echo "</pre>";


        for ($i = 0; $i < count($allUsers); $i++) {
            if ($allUsers[$i]["username"] === $username) {
                echo "same as $i";
                return true;
            }
        }
        return false;
    }

    function get_all_users($fileName) {
        if (file_exists($fileName)) {
            return json_decode(file_get_contents($fileName), true);
        } else {
            file_put_contents($fileName, []);
            return [];
        }
    }
?>