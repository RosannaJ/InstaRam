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
							$post["comments"][$key] = $comment;

							// decide whether the comment should be deletable
							if ($comment["username"] == get_username($_SESSION["userID"])) {
								$post["comments"][$key]["shouldDisplay"] = true;
							}
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
			} // if
		} // foreach
	} // else if

	// echo the profiles that meet the filter conditions
	else if (isset($_GET["connection"]) || isset($_GET["search"]) || isset($_GET["grade"])) {
		$users = get_all_user_data();
		$newArray = [];
		$search = isset($_GET["search"]) ? $_GET["search"] : "";
		$connectionFilter = isset($_GET["connection"]) ? $_GET["connection"] : "all";
		$gradeFilter = isset($_GET["grade"]) ? $_GET["grade"] : "all";

		for ($i = 0; $i < count($users); $i++) {
			
			// check if name or username contains search terms
			if (empty($search) || stripos($users[$i]["name"], $search) !== false || stripos($users[$i]["username"], $search) !== false) {
				
				// check if meets filter condition
				if (strcmp($connectionFilter, "all") == 0 || ($users[$i]["connection"] == $connectionFilter 
					&& (strcmp($gradeFilter, "all") == 0 || $users[$i]["grade"] == $gradeFilter))) {
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

				} // if
			} // if
		} // for
		
		// echo requested profiles
		echo json_encode($newArray, JSON_PRETTY_PRINT);
	} // else if

	// toggle liking the post with requested UID if logged in (or check whether the post has been liked)
	else if (array_key_exists("action", $_GET) && ($_GET["action"] == "like" || $_GET["action"] == "checkIfLiked") 
		&& array_key_exists("UID", $_GET)			// TODO: check if uid is valid (check with postIdentifier file)
		&& array_key_exists("userID", $_SESSION)) { // TODO: check if user is valid?

		update_post($_GET["UID"], function(&$post) {
			$alreadyLiked = array_search($_SESSION["userID"], $post["likes"]);
			$userInfo = get_user_data(get_user_of_post($_GET["UID"]));

			// return whether current user has liked the post if that's what's requested
			if ($_GET["action"] == "checkIfLiked") {
				echo json_encode(["isLiked" => ($alreadyLiked === false) ? false : true], JSON_PRETTY_PRINT);
			} // if

			// toggle "like"
			else if ($alreadyLiked === false) {

				// add like
				$post["likes"][] = $_SESSION["userID"];

				// add new notification if user was not liking own post
				//if ($userInfo["UID"] != $_SESSION["userID"]) { //**uncomment
					$userInfo["notifications"][] = [
						"action" => "like",
						"user" => $_SESSION["userID"],
						"postUID" => $_GET["UID"],
						"message" => "liked your post.",
						"read" => false
					];

					// update info to json file
					update_user_data($userInfo);
				//} // if

				echo json_encode(["isLiked" => true], JSON_PRETTY_PRINT);
			} else {
				unset($post["likes"][$alreadyLiked]);
				$post["likes"] = array_values($post["likes"]);

				// find notification and delete if it exists
				if (isset($userInfo["notifications"])) {
					foreach ($userInfo["notifications"] as $key => $notif) {
						if ($notif["user"] == $_SESSION["userID"] && $notif["action"] == "like") {

							// delete notification
							unset($userInfo["notifications"][$key]);
							$userInfo["notifications"] = array_values($userInfo["notifications"]);

							// update info to json file
							update_user_data($userInfo);
							break;
						} // if
					} // foreach
				} // if

				echo json_encode(["isLiked" => false], JSON_PRETTY_PRINT);
			} // else
		});
	} // else of

	// add comment to post with requested UID
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "addComment" 
		&& array_key_exists("UID", $_GET)			// TODO: check if uid is valid (check with postIdentifier file)
		&& array_key_exists("userID", $_SESSION)) {
		
		echo json_encode($_POST, JSON_PRETTY_PRINT); // temp

		if (!empty($_POST["text"])) {

			// clean data
			$_POST["text"] = clean_data($_POST["text"]);

			// add new comment
			update_post($_GET["UID"], function(&$post) {
				$userInfo = get_user_data(get_user_of_post($_GET["UID"]));
				$identifierFileName = "commentUID.txt";
				$newUID = 0;

				// get next uid if file exists
				if (file_exists($identifierFileName)) {
					$newUID = file_get_contents($identifierFileName);
				} // if
			
				// update uid
				file_put_contents($identifierFileName, $newUID + 1);

				$post["comments"][] = [
					"user" => $_SESSION["userID"], // **add fetch in javascript for username from id?
					"text" => $_POST["text"],	//** clean data first? **
					"UID" => $newUID
				];

				// add new notification if user was not commenting on own post
				//if ($userInfo["UID"] != $_SESSION["userID"]) { //**uncomment
					$userInfo["notifications"][] = [
						"action" => "comment",
						"commentUID" => $newUID,
						"postUID" => $_GET["UID"],
						"user" => $_SESSION["userID"],
						"message" => "commented '{$_POST["text"]}' on your post.",
						"read" => false
					];

					// update info to json file
					update_user_data($userInfo);
				//}
			});
		} // if
	} // else if

	// edit caption
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "editPost" 
		&& array_key_exists("UID", $_GET)			// TODO: check if uid is valid (check with postIdentifier file)
		&& array_key_exists("userID", $_SESSION)) {

		// clean data
		$_POST["postCaption"] = clean_data($_POST["postCaption"]);

		// update post caption
		if (get_user_of_post($_GET["UID"]) == $_SESSION["userID"]) {
			update_post($_GET["UID"], function(&$post) {
				$post["caption"] = $_POST["postCaption"];
			});
		} // if

		echo json_encode(["edited" => $_POST["postCaption"]], JSON_PRETTY_PRINT);

	} // else if

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
				
				// get user info
				$userInfo = get_user_data(get_user_of_post($_GET["UID"]));

				// check that comment UID matches and user is the one who posted it
				if ($comment["UID"] != $_GET["commentUID"] || $comment["user"] != $_SESSION["userID"]) { continue; }

				// delete requested comment
				unset($post["comments"][$index]);
				$post["comments"] = array_values($post["comments"]);

				// find notification and delete if it exists
				if (isset($userInfo["notifications"])) {
					foreach ($userInfo["notifications"] as $key => $notif) {

						// find notification
						if ($notif["user"] == $_SESSION["userID"] && $notif["action"] == "comment" 
						&& $notif["commentUID"] == $_GET["commentUID"]) {

							// delete notification
							unset($userInfo["notifications"][$key]);
							$userInfo["notifications"] = array_values($userInfo["notifications"]);

							// update info to json file
							update_user_data($userInfo);
							break;
						} // if
					} // foreach
				} // if
				echo json_encode(["commentDeleted" => true], JSON_PRETTY_PRINT);
			} // foreach
		});
	} // else if

	// toggles between sending/unsending friend requests and accepting/deleting friends depending on the current state of the two users
	// echos the new message to be displayed on the button
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "friend" 
		&& array_key_exists("user", $_GET)
		&& array_key_exists("userID", $_SESSION)) {
		
		// get UID of other user
		$otherID = get_userID($_GET['user']);

		// get user info of both users
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
				
				// find and delete friend request notification for currentUser if it exists
				if (isset($currentUserData["notifications"])) {
					foreach ($currentUserData["notifications"] as $key => $notif) {
						if ($notif["user"] == $otherID && $notif["action"] == "friendRequest") {

							// delete notification
							unset($currentUserData["notifications"][$key]);
							$currentUserData["notifications"] = array_values($currentUserData["notifications"]);
							break;
						} // if
					} // foreach
				} // if

				// add "new friend" notification to other user
				$otherUserData["notifications"][] = [
					"action" => "friended",
					"user" => $_SESSION["userID"],
					"message" => " is now your friend.",
					"read" => false
				];

				// add "new friend" notification to current user
				$currentUserData["notifications"][] = [
					"action" => "friended",
					"user" => $otherID,
					"message" => " is now your friend.",
					"read" => false
				];

				echo json_encode(["message" => "Delete Friend"], JSON_PRETTY_PRINT);
			} // if
			
			// SEND FRIEND REQUEST
			// check that a friend request has not been sent already
			else if (!isset($otherUserData['friendRequests']) || !in_array($_SESSION["userID"], $otherUserData['friendRequests'])) {

				// add friend request
				$otherUserData['friendRequests'][] = $_SESSION["userID"];

				// add new notification if user was not commenting on own post
				$otherUserData["notifications"][] = [
					"action" => "friendRequest",
					"user" => $_SESSION["userID"],
					"message" => " has sent you a friend request.",
					"read" => false
				];

				echo json_encode(["message" => "Unsend Friend Request"], JSON_PRETTY_PRINT);
			} // else if
			
			// UNSEND FRIEND REQUEST
			else {

				// remove friend request (remove current user from other user's requests list)
				$index = array_search($_SESSION["userID"], $otherUserData['friendRequests']);
				unset($otherUserData['friendRequests'][$index]);
				$otherUserData['friendRequests'] = array_values($otherUserData['friendRequests']);

				// find friend request notification and delete
				foreach ($otherUserData["notifications"] as $key => $notif) {
					if ($notif["user"] == $_SESSION["userID"] && $notif["action"] == "friendRequest") {

						// delete notification
						unset($otherUserData["notifications"][$key]);
						$otherUserData["notifications"] = array_values($otherUserData["notifications"]);
						break;
					} // if
				} // foreach

				echo json_encode(["message" => "Send Friend Request"], JSON_PRETTY_PRINT);
			} // else
		} // if
		
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

			// find "is friends with" notification and delete if it exists for otherUser
			if (isset($otherUserData["notifications"])) {
				foreach ($otherUserData["notifications"] as $key => $notif) {
					if ($notif["user"] == $_SESSION["userID"] && $notif["action"] == "friended") {

						// delete notification
						unset($otherUserData["notifications"][$key]);
						$otherUserData["notifications"] = array_values($otherUserData["notifications"]);
						break;
					} // if
				} // foreach
			} // if

			// find "is friends with" notification and delete if it exists for currentUser
			if (isset($currentUserData["notifications"])) {
				foreach ($currentUserData["notifications"] as $key => $notif) {
					if ($notif["user"] == $otherID && $notif["action"] == "friended") {

						// delete notification
						unset($currentUserData["notifications"][$key]);
						$currentUserData["notifications"] = array_values($currentUserData["notifications"]);
						break;
					} // if
				} // foreach
			} // if

			echo json_encode(["message" => "Send Friend Request"], JSON_PRETTY_PRINT);
		} // else

		// update userinfo.json files
		update_user_data($otherUserData);
		update_user_data($currentUserData);
	} // else if

	// delete the post with the requested UID if it exists
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "deletePost" 
	&& array_key_exists("UID", $_GET) && array_key_exists("userID", $_SESSION)) {
		$reqPostUser = get_user_of_post($_GET["UID"]);

		// check if post to delete is the current user's post
		if ($_SESSION["userID"] == $reqPostUser) {
			$dest = "users/" . $reqPostUser . "/posts/posts.json";
			$posts = json_decode(file_get_contents($dest), true);

			// find post with requested UID
			foreach ($posts as $index => $post) {
				if ($post["UID"] == $_GET["UID"]) {

					// delete post info 
					unset($posts[$index]);

					// delete post image
					if (file_exists("users/" . $reqPostUser . "/posts/" . $post["UID"] . $post)) {
						unlink("identifier.txt");
					} // if

					break;
				} // if
			} // foreach

			// update posts.json file
			file_put_contents($dest, json_encode($posts, JSON_PRETTY_PRINT));

		} // if

		echo json_encode(["deleted" => true], JSON_PRETTY_PRINT);
	} // else if

	// echos current user's notifications
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "notifs"
	&& array_key_exists("userID", $_SESSION)) {

		// get user data
		$userInfo = get_user_data($_SESSION["userID"]);
		$notifications = [];

		// get notifications if there are any
		if (isset($userInfo["notifications"])) {
			$notifications = $userInfo["notifications"];
		}

		// loop through notifications
		foreach ($notifications as $key => $notif) {
			$notifications[$key]["username"] = get_username($notif["user"]);

			// set image src if notification is related to a post
			if (isset($notifications[$key]["postUID"])) {
				$imageFileType = "";

				// find post with matching UID to get imageFileType
				foreach (get_all_posts()[$notif["user"]] as $post) {
					if ($post["UID"] == $notif["postUID"]) {
						$imageFileType = $post["imageFileType"];
						break;
					} // if
				} // foreach

				// set src of post image
				$notifications[$key]["src"] = "users/" . $notif["user"] . "/posts/" . $notif["postUID"] . "." . $imageFileType;
			} // if

			$notifications[$key]["pfpsrc"] = $userInfo["imageFileType"];
		} // foreach

		echo json_encode($notifications, JSON_PRETTY_PRINT);
	} // else if

	else if (array_key_exists("action", $_GET) && $_GET["action"] == "deleteNotif"
	&& array_key_exists("userID", $_SESSION) && array_key_exists("user", $_GET) && array_key_exists("notifAction", $_GET)) {

		$userInfo = get_user_data($_SESSION["userID"]);

		// find notification and delete if it exists
		if (isset($userInfo["notifications"])) {
			foreach ($userInfo["notifications"] as $key => $notif) {
				if ($notif["user"] == $_GET["user"] && $notif["action"] == $_GET["notifAction"]) {

					// if it's related to a comment, check if the commentUID is the same
					if (isset($notif["commentUID"]) && isset($_GET["commentUID"]) && $_GET["commentUID"] != $notif["commentUID"]) { break; }
					
					// delete notification
					unset($userInfo["notifications"][$key]);
					$userInfo["notifications"] = array_values($userInfo["notifications"]);

					// update info to json file
					update_user_data($userInfo);
					break;
				
				} // if
			} // foreach
		} // if
	} // else if

	// deletes all notifications
	else if (array_key_exists("action", $_GET) && $_GET["action"] == "deleteAllNotifs"
	&& array_key_exists("userID", $_SESSION)) {

		// get user info of currently logged in user
		$userInfo = get_user_data($_SESSION["userID"]);

		// delete all notifications
		unset($userInfo["notifications"]);

		// update info to json file
		update_user_data($userInfo);

		echo json_encode(["deleted" => true], JSON_PRETTY_PRINT);
	} // else if

	// prevent end of json errors
	else {
		echo json_encode(["fetchFailed" => true], JSON_PRETTY_PRINT);
	} // else
?>