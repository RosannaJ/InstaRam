<?php
	session_start();
	include "functions.php";

	// return info of post with requested UID
	if (!array_key_exists("action", $_GET) 
		&& array_key_exists("UID", $_GET)) { // is page check needed? 
		
		// find post with requested uid and var_dump
		foreach (get_all_posts() as $user) {
			foreach ($user as $post) {
				if ($post["UID"] == $_GET["UID"]) {
					echo json_encode($post, JSON_PRETTY_PRINT);
					break 2;
				}
			}
		}
	}

	// toggle liking the post with requested UID if logged in (or check whether the post has been liked)
	else if (array_key_exists("action", $_GET) && ($_GET["action"] == "like" || $_GET["action"] == "checkIfLiked") 
		&& array_key_exists("UID", $_GET)			// TODO: check if uid is valid (check with postIdentifier file)
		&& array_key_exists("username", $_SESSION) && username_exists($_SESSION["username"], get_all_usernames())) {

		update_post($_GET["UID"], function(&$post) {
			$alreadyLiked = array_search($_SESSION["username"], $post["likes"]);

			// return whether current user has liked the post if that's what's requested
			if ($_GET["action"] == "checkIfLiked") {
				echo json_encode(["isLiked" => ($alreadyLiked === false) ? false : true], JSON_PRETTY_PRINT);
			}

			// toggle "like"
			else if ($alreadyLiked === false) {
				$post["likes"][] = $_SESSION["username"];
				echo json_encode(["isLiked" => true], JSON_PRETTY_PRINT);
			} else {
				unset($post["likes"][$alreadyLiked]);
				array_values($post["likes"]);
				echo json_encode(["isLiked" => false], JSON_PRETTY_PRINT);
			}
		});
	}

	// add comment to post with requested UID
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "addComment" 
		&& array_key_exists("UID", $_GET)			// TODO: check if uid is valid (check with postIdentifier file)
		&& array_key_exists("username", $_SESSION) && username_exists($_SESSION["username"], get_all_usernames())) {
		
		echo json_encode($_POST, JSON_PRETTY_PRINT); // temp

		if (!empty($_POST["text"])) {

			// add new comment
			update_post($_GET["UID"], function(&$post) {
				$identifierFileName = "commentUID.txt";
				$newUID = 0;

				// get next uid if file exists
				if (file_exists($identifierFileName)) {
					$newUID = file_get_contents($identifierFileName);
				}
			
				// update uid
				file_put_contents($identifierFileName, $newUID + 1);

				$post["comments"][] = [
					"username" => $_SESSION["username"],
					"text" => $_POST["text"],	//** clean data first? **
					"UID" => $newUID
				];
			});
		}
	}

	// delete comment with requested UID on post with requested UID
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "deleteComment" 
		&& array_key_exists("UID", $_GET)			// TODO: check if uid is valid (check with postIdentifier file)
		&& array_key_exists("commentUID", $_GET)	// TODO: check if uid is valid 
		&& array_key_exists("username", $_SESSION) && username_exists($_SESSION["username"], get_all_usernames())) {

		// delete comment
		update_post($_GET["UID"], function(&$post) {
			
			// check if comments is a key in array?**

			// loop through comments
			foreach ($post["comments"] as $index => $comment) {

				if ($comment["UID"] != $_GET["commentUID"] || $comment["username"] != $_SESSION["username"]) { continue; }

				// delete requested comment
				echo json_encode($post["comments"][$index], JSON_PRETTY_PRINT);
				unset($post["comments"][$index]);
				$post["comments"] = array_values($post["comments"]);
			}
			
		});
	}

	// prevent end of json errors // is this a good idea??
	else {
		echo json_encode(["fetchFailed" => true], JSON_PRETTY_PRINT);
	}
?>