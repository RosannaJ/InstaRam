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

					// update username on comments
					if (isset($post["comments"])) {
						foreach ($post["comments"] as $key => $comment) {
							$comment["username"] = get_username($comment["user"]);
							unset($comment["user"]);
							$post["comments"][$key] = $comment;
						} // foreach
					} // if

					// update username on likes
					if (isset($post["likes"])) {
						foreach ($post["likes"] as $key => $user) {
							$user = get_username($user);
							$post["likes"][$key] = $user;
						} // foreach
					} // if
					
					// echo post info
					echo json_encode($post, JSON_PRETTY_PRINT);
					break 2;
				} // if
			} // foreach
		} // foreach
	} // if

	// echos info of requested user
	else if (!array_key_exists("action", $_GET) 
		&& array_key_exists("user", $_GET)) {

		// find requested user and echo their profile info
		foreach (get_all_user_data() as $user) {
			if ($user['UID'] == $_GET['user']) {
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
						"UID" => $users[$i]['UID'],
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
		&& array_key_exists("userID", $_SESSION)) { // TODO: check if user is valid?

		update_post($_GET["UID"], function(&$post) {
			$alreadyLiked = array_search($_SESSION["userID"], $post["likes"]);

			// return whether current user has liked the post if that's what's requested
			if ($_GET["action"] == "checkIfLiked") {
				echo json_encode(["isLiked" => ($alreadyLiked === false) ? false : true], JSON_PRETTY_PRINT);
			}

			// toggle "like"
			else if ($alreadyLiked === false) {
				$post["likes"][] = $_SESSION["userID"];
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
		&& array_key_exists("userID", $_SESSION)) {
		
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
					"user" => $_SESSION["userID"], // **add fetch in javascript for username from id?
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
		&& array_key_exists("userID", $_SESSION)) {

		// delete comment
		update_post($_GET["UID"], function(&$post) {
			
			// check if comments is a key in array?**

			// loop through comments
			foreach ($post["comments"] as $index => $comment) {

				if ($comment["UID"] != $_GET["commentUID"] || $comment["user"] != $_SESSION["userID"]) { continue; }

				// delete requested comment
				unset($post["comments"][$index]);
				$post["comments"] = array_values($post["comments"]);
				echo json_encode(["commentDeleted" => true], JSON_PRETTY_PRINT);
			}
			
		});
	}

	// toggles between sending/unsending friend requests and accepting/deleting friends depending on the current state of the two users
	// echos the new message to be displayed on the button
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "friend" 
		&& array_key_exists("user", $_GET)
		&& array_key_exists("userID", $_SESSION)) {
			
		$otherID = get_userID($_GET['user']);

		$otherUserData = get_user_data($otherID);
		$currentUserData = get_user_data($_SESSION["userID"]);

		// check that they are not friends
		if (!isset($otherUserData['friends']) || !in_array($_SESSION["userID"], $otherUserData['friends'])) { // should both users be checked?

			// ADD FRIEND
			// check if other user is already in current user's friend request list
			if (isset($currentUserData['friendRequests']) && in_array($otherID, $currentUserData['friendRequests'])) {

				// remove from requests list
				$index = array_search($otherID, $currentUserData['friendRequests']);
				unset($currentUserData['friendRequests'][$index]);

				// add to friends list
				$otherUserData['friends'][] = $_SESSION["userID"];
				$currentUserData['friends'][] = $otherID;

				echo json_encode(["message" => "Delete Friend"], JSON_PRETTY_PRINT);
			} 
			
			// SEND FRIEND REQUEST
			// check that a friend request has not been sent already
			else if (!isset($otherUserData['friendRequests']) || !in_array($_SESSION["userID"], $otherUserData['friendRequests'])) {

				// add friend request
				$otherUserData['friendRequests'][] = $_SESSION["userID"];

				echo json_encode(["message" => "Unsend Friend request"], JSON_PRETTY_PRINT);
			} 
			
			// UNSEND FRIEND REQUEST
			else {

				// remove friend request (remove current user from other user's requests list)
				$index = array_search($_SESSION["userID"], $otherUserData['friendRequests']);
				unset($otherUserData['friendRequests'][$index]);
				$otherUserData['friendRequests'] = array_values($otherUserData['friendRequests']);

				echo json_encode(["message" => "Send Friend Request"], JSON_PRETTY_PRINT);
			}
		} 
		
		// DELETE FRIEND
		else {
			
			// delete other user from current user's friend list
			$index = array_search($otherID, $currentUserData['friends']);
			unset($currentUserData['friends'][$index]);
			$currentUserData['friends'] = array_values($currentUserData['friends']);

			// delete current user from other user's friend list
			$index = array_search($_SESSION["userID"], $otherUserData['friends']);
			unset($otherUserData['friends'][$index]);
			$otherUserData['friends'] = array_values($otherUserData['friends']);

			echo json_encode(["message" => "Send Friend Request"], JSON_PRETTY_PRINT);
		}

		// update userinfo.json files
		update_user_data($otherUserData["UID"], $otherUserData);
		update_user_data($currentUserData["UID"], $currentUserData);
	}

	else if (array_key_exists("action", $_GET) && $_GET["action"] == "deletePost" 
	&& array_key_exists("UID", $_GET)) {
		$reqPostUser = get_user_of_post($_GET["UID"]);

		// check if post to delete is the current user's post
		if ($_SESSION["userID"] == $reqPostUser) {
			$dest = "users/" . $reqPostUser . "/posts/posts.json";
			$posts = json_decode(file_get_contents($dest), true);

			foreach ($posts as $index => $post) {
				if ($post["UID"] == $_GET["UID"]) {
					unset($posts[$index]);
					break;
				} // if
			} // foreach
		} // if

		echo json_encode(["deleted" => true], JSON_PRETTY_PRINT);
	} // else if

	// prevent end of json errors
	else {
		echo json_encode(["fetchFailed" => true], JSON_PRETTY_PRINT);
	}
?>