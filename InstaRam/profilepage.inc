<?php
	include "postLightbox.inc";

	if (array_key_exists("user", $_GET) && username_exists($_GET["user"])) {
		
		// loop through all users to find current one
		foreach (get_all_user_data() as $user) {
			$posts = [];

			// display profile info
			if ($user["username"] === $_GET["user"]) {
				$folderName = "users/{$user['UID']}/posts/"; //  posts folder

				// display username
				echo "<h1>" . $user['username'] . "</h1>";

				// display name
				echo $user['name'] . "<br>";

				// display profile pic
				if (!empty($user["imageFileType"])) {
					echo "<img src='users/{$user['UID']}/pfp.{$user['imageFileType']}' width='240'>"; // delete width
				}

				// display bio
				echo "<br>Bio: " . $user['bio'] . "<br><br>";

				// DISPLAY FRIEND REQUEST BUTTON AND DECLINE
				// check that this is not the current user's own profile page
				if (isset($_SESSION["userID"]) && strcmp($_SESSION["userID"], $user['UID']) !== 0) {
					$message = "";
					$pageUserID = get_userID($user['username']);
					$hideButton = "";

					// check that current user and profile page user are not friends
					if (!contains($_SESSION["userID"], $pageUserID, "friends")) {

						// set the text on the friend request button
						$message = contains($pageUserID, $_SESSION["userID"], "friendRequests") ? "Accept Friend Request" : "Send Friend Request";

						if (contains($_SESSION["userID"], $pageUserID, "friendRequests")) {
							$message = "Unsend Friend Request";
						}

						if (strcmp($message, "Accept Friend Request") === 0) {
							$hideButton = "hideDeclineButton();";
						}

						// display friend request button
						echo "<input type='button' id='friendRequest' value='$message' onclick='toggleFriend(\"" . $user['username'] . "\");$hideButton'>";
					} else {
						echo "<input type='button' id='friendRequest' value='Delete Friend' onclick='toggleFriend(\"" . $user['username'] . "\")'>";
					}

					// display decline button if pending friend request
					if (strcmp($message, "Accept Friend Request") === 0) {
						echo "<input type='button' id='decline' value='Decline Friend Request' onclick='toggleFriend(\"" . $user['username'] . "\");toggleFriend(\"" . $user['username'] . "\");hideDeclineButton();'>";
					}
					
				}

				// display friends --- temp?

				echo "<br><br>Refresh to update: ";
				
				if (isset($user['friends'])) {
					echo "<br>";
					echo "This person has " . count($user['friends']) . " friend(s).";
					echo "<br>";

					echo "<br>Friends: ";
					foreach ($user['friends'] as $friend) {
						echo $friend . ", ";
					}
				}

				if (isset($user['friendRequests'])) {
					echo "<br>Friend Requests: ";
					foreach ($user['friendRequests'] as $request) {
						echo $request . ", "; 
					}
				}

				// temp
				echo "<hr>POSTS:<br>";

				// get all posts
				$posts = get_all_posts();

				//check if the user has any posts
				if (array_key_exists($user['UID'], $posts)) {

					// get posts of user
					$posts = $posts[$user['UID']];

					// sort posts by UID
					usort($posts, "cmp_UID");

					// display posts
					echo "<div id='thumbnails'>\n";
					foreach ($posts as $post) {
						$dest = "users/{$user['UID']}/posts/" . $post['UID'] . "." . $post['imageFileType'];
						echo "<div class='card' id='{$post['UID']}' onclick='displayLightBox(\"$dest\")'>";
						echo "<img src='$dest' width='180' class='thumbnail'>\n";
						echo "</div>";
					}
					echo "</div>";
				}
				break;
			}
		}
	} else {
		echo "User does not exist.";
	}
?>