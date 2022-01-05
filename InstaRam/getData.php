<?php
	session_start();
	include "functions.php";

	// echos info of post with requested UID
	if (!array_key_exists("action", $_GET) 
		&& array_key_exists("UID", $_GET)) { // is page check needed? 
		
		// find post with requested uid
		foreach (get_all_posts() as $user) {
			foreach ($user as $post) {
				if ($post["UID"] == $_GET["UID"]) {
					echo json_encode($post, JSON_PRETTY_PRINT);
					break 2;
				}
			}
		}
	}

	// echos info of requested user
	else if (!array_key_exists("action", $_GET) 
		&& array_key_exists("user", $_GET)) {

		// find requested user and echo their profile info
		foreach (get_all_user_data() as $user) {
			if ($user['username'] == $_GET['user']) {
				echo json_encode([
					"username" => $user['username'],
					"name" => $user['name'],
					"connection" => $user['connection'],
					"grade" => $user['grade'],
					"bio" => $user['bio'],
					"birthdate" => $user['birthdate'],
				], JSON_PRETTY_PRINT);
				break;
			}
		}
	}

	else if (isset($_GET["connection"]) || isset($_GET["search"])) {
		$users = get_all_user_data();
		$newArray = [];
		$search = isset($_GET["search"]) ? $_GET["search"] : "";
		$connectionFilter = isset($_GET["connection"]) ? $_GET["connection"] : "all";

		for ($i = 0; $i < count($users); $i++) {
			
			// check if name or username contains search terms
			if (empty($search) || stripos($users[$i]["name"], $search) !== false || stripos($users[$i]["username"], $search) !== false) {
				
				// check if meets filter condition
				if (strcmp($connectionFilter, "all") == 0 || $users[$i]["connection"] == $connectionFilter) {
					//$newArray[] = $users[$i];
					$newArray[] = [
						"username" => $users[$i]['username'],
						"name" => $users[$i]['name'],
						"connection" => $users[$i]['connection'],
						"grade" => $users[$i]['grade'],
						"bio" => $users[$i]['bio'],
						"birthdate" => $users[$i]['birthdate'],
						"imageFileType" => $users[$i]['imageFileType']
					];
				}
			}
		}
		
		// echo requested profiles
		echo json_encode($newArray, JSON_PRETTY_PRINT);
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
				$post["likes"] = array_values($post["likes"]);
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

	// toggles between sending/unsending friend requests and accepting/deleting friends depending on the current state of the two users
	// echos the new message to be displayed on the button
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "friend" 
		&& array_key_exists("user", $_GET)
		&& array_key_exists("username", $_SESSION) && username_exists($_SESSION["username"], get_all_usernames())) {
		$otherUserData = get_user_data($_GET['user']);
		$currentUserData = get_user_data($_SESSION["username"]);

		// check that they are not friends
		if (!isset($otherUserData['friends']) || !in_array($_SESSION['username'], $otherUserData['friends'])) { // should both users be checked?

			// ADD FRIEND
			// check if other user is already in current user's friend request list
			if (isset($currentUserData['friendRequests']) && in_array($_GET['user'], $currentUserData['friendRequests'])) {

				// remove from requests list
				$index = array_search($_GET['user'], $currentUserData['friendRequests']);
				unset($currentUserData['friendRequests'][$index]);

				// add to friends list
				$otherUserData['friends'][] = $_SESSION['username'];
				$currentUserData['friends'][] = $_GET['user'];

				echo json_encode(["message" => "Delete Friend"], JSON_PRETTY_PRINT);
			} 
			
			// SEND FRIEND REQUEST
			// check that a friend request has not been sent already
			else if (!isset($otherUserData['friendRequests']) || !in_array($_SESSION['username'], $otherUserData['friendRequests'])) {

				// add friend request
				$otherUserData['friendRequests'][] = $_SESSION['username'];

				echo json_encode(["message" => "Unsend friend request"], JSON_PRETTY_PRINT);
			} 
			
			// UNSEND FRIEND REQUEST
			else {

				// remove friend request (remove current user from other user's requests list)
				$index = array_search($_SESSION['username'], $otherUserData['friendRequests']);
				unset($otherUserData['friendRequests'][$index]);
				$otherUserData['friendRequests'] = array_values($otherUserData['friendRequests']);

				echo json_encode(["message" => "Send Friend Request"], JSON_PRETTY_PRINT);
			}
		} 
		
		// DELETE FRIEND
		else {
			
			// delete other user from current user's friend list
			$index = array_search($_GET['user'], $currentUserData['friends']);
			unset($currentUserData['friends'][$index]);
			$currentUserData['friends'] = array_values($currentUserData['friends']);

			// delete current user from other user's friend list
			$index = array_search($_SESSION['username'], $otherUserData['friends']);
			unset($otherUserData['friends'][$index]);
			$otherUserData['friends'] = array_values($otherUserData['friends']);

			echo json_encode(["message" => "Send Friend Request"], JSON_PRETTY_PRINT);
		}

		// update userinfo.json files
		update_user_data($_GET['user'], $otherUserData);
		update_user_data($_SESSION['username'], $currentUserData);
	}

	// prevent end of json errors
	else {
		echo json_encode(["fetchFailed" => true], JSON_PRETTY_PRINT);
	}
?>