<?php
    session_start();
    include "functions.php";

    // process form data if submitted
    include "login.php";
    include "signup.php";

    // decide which page to show
    if (!array_key_exists("page", $_SESSION)) {
        $_SESSION["page"] = 1;
    }
    if (array_key_exists("page", $_GET)) {
        $_SESSION["page"] = $_GET["page"];
    }

    // delete contents of "users" folder
    if (array_key_exists("action", $_GET) && $_GET["action"] == "del") {
        $fileName = "users/";

        // delete contents of folder
        if (file_exists($fileName)) {

            // loop through "users" folder
            if (is_dir($fileName)) {
                $dh = opendir($fileName);
                while (($file = readdir($dh)) !== false) {
                    $postsFolder = $fileName . $file . "/posts/";

                    // check if is a folder (folder for each user)
                    if (!is_dir($file)) {

                        // delete contents of folder
                        if (is_dir($fileName . $file . "/")) {
                            $dh2 = opendir($fileName . $file . "/");
                            while (($newFile = readdir($dh2)) !== false) {

                                // delete files
                                if (!is_dir($fileName . $file . "/" . $newFile)) {
                                    unlink($fileName . $file . "/" . $newFile);
                                } 

                                // delete folders
                                else if ($newFile !== "." && $newFile !== ".."){
                                    rmdir($fileName . $file . "/" . $newFile);
                                }
                            }
                            closedir($dh2);

                            // delete user folder
                            rmdir($fileName . $file);
                        }
                    }
                }
                closedir($dh);
            }
        }
    }

    // show page content
    include "header.inc";
    	
    if ($_SESSION["page"] == 1) {
        echo "page 1";
        include "loginform.inc";
    } else if ($_SESSION["page"] == 2) {
        echo "page 2";
        include "signupform.inc";
    } else if ($_SESSION["page"] == 3) {
        echo "page 3";
        include "explore.php";
    } else {
        echo "page does not exist";
    }

    include "footer.inc";

?>


