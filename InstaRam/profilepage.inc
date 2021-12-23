<?php
	include "postLightbox.inc";

	if (array_key_exists("user", $_GET) && username_exists($_GET["user"], get_all_usernames())) {
		
		// loop through all users to find current one
		foreach (get_all_user_data() as $user) {
			$posts = [];

			// display profile info
			if ($user["username"] === $_GET["user"]) {
				$folderName = "users/{$user['username']}/posts/"; //  posts folder

				// display username
				echo "<h1>" . $user['username'] . "</h1>";

				// display name
				echo $user['name'] . "<br>";

				// display profile pic
				if (!empty($user["imageFileType"])) {
					echo "<img src='users/{$user['username']}/pfp.{$user['imageFileType']}' width='240'>";
				}

				// display bio
				echo "<br>Bio: " . $user['bio'] . "<br><br>";

				// temp
				echo "<hr>POSTS:<br>";

				// get all posts
				$posts = get_all_posts();

				//check if the user has any posts
				if (array_key_exists($user['username'], $posts)) {

					// get posts of user
					$posts = $posts[$user['username']];

					// sort posts by UID
					usort($posts, "cmp_UID");

					// display posts
					echo "<div id='thumbnails'>\n";
					foreach ($posts as $post) {
						$dest = "users/{$user['username']}/posts/" . $post['UID'] . "." . $post['imageFileType'];
						echo "<img src='$dest' width='240' class='thumbnail' onclick='displayLightBox(\"$dest\")'>\n";
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