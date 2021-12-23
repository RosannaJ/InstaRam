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
	if (array_key_exists("action", $_GET) && ($_GET["action"] == "like" || $_GET["action"] == "checkIfLiked") 
		&& array_key_exists("UID", $_GET)			// TODO: check if uid is valid (check with postIdentifier file)
		&& array_key_exists("username", $_SESSION)) {

		// find post with uid
		$reqPostUsername = get_username_of_post($_GET["UID"]);
		
		// get contents of file
		$dest = "users/" . $reqPostUsername . "/posts/posts.json";
		$posts = json_decode(file_get_contents($dest), true);

		// add current username to "likes" in post
		for ($i = 0; $i < count($posts); $i++) {

			// find post with requested UID
			if ($posts[$i]["UID"] == $_GET["UID"]) {
				$alreadyLiked = array_search($_SESSION["username"], $posts[$i]["likes"]);

				// return whether current user has liked the post if that's what's requested
				if ($_GET["action"] == "checkIfLiked") {
					echo json_encode(["isLiked" => ($alreadyLiked === false) ? false : true], JSON_PRETTY_PRINT);
				}

				// toggle "like"
				else if ($alreadyLiked === false) {
					$posts[$i]["likes"][] = $_SESSION["username"];
					echo json_encode(["isLiked" => true], JSON_PRETTY_PRINT);
				} else {
					unset($posts[$i]["likes"][$alreadyLiked]);
					array_values($posts[$i]["likes"]);
					echo json_encode(["isLiked" => false], JSON_PRETTY_PRINT);
				}
				break;
			}
		}

		// update posts.json file
		file_put_contents($dest, json_encode($posts, JSON_PRETTY_PRINT));
	}

?>