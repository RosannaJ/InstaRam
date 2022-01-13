<?php 
	
	include "postLightbox.inc";

	
	// if not logged in, display all? or redirect to login page?

	echo "<br>HOME PAGE: Your friend's posts are displayed here.";

	// show posts if logged in
	if (isset($_SESSION["userID"])) {
		$allPosts = [];
		$userData = get_user_data($_SESSION["userID"]);

		if (isset($userData['friends'])) {
			// get friends of current user
			$friends = $userData['friends'];

			// get all posts
			$allPosts = get_posts_separated($friends);
		}

		if (count($allPosts) > 0) {
			// sort posts by UID
			usort($allPosts, "cmp_UID");

			echo "<div id='thumbnails'>";

			// loop through all posts
			foreach ($allPosts as $post) {
				$user = get_username_of_post($post['UID']);
				$imageFile = "users/$user/posts/" . $post['UID'] . "." . $post['imageFileType'];

				// display username, image, and caption for each post
				echo "<div class='card' id='{$post['UID']}' onclick='displayLightBox(\"$imageFile\")'>";
				echo "<a href='?page=6&user=" . $user . "' onclick='event.stopPropagation()'>" . $user . "</a>";
				echo "<img src='$imageFile' class='thumbnail'>";
				echo "<p>" . $post['caption'] . "</p>";
				echo "</div>";
			}

			echo "</div>";
		}
		
	}
?>