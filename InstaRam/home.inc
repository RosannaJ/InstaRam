<?php 
	
	include "postLightbox.inc";

	// echo "<br>HOME PAGE: Your friend's posts are displayed here.";

	echo "<h1 id='title'>Home</h1>";
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

		// add own posts
		$allPosts = array_merge($allPosts, get_posts_separated([$_SESSION["userID"]]));

		if (count($allPosts) > 0) {
			
			// sort posts by UID
			usort($allPosts, "cmp_UID");

			echo "<div id='thumbnails'>";

			// loop through all posts
			foreach ($allPosts as $post) {
				$user = get_user_of_post($post['UID']);
				$imageFile = "users/$user/posts/" . $post['UID'] . "." . $post['imageFileType'];

				// display username, image, and caption for each post
				echo "<div class='card' id='{$post['UID']}' onclick='displayLightBox(\"$imageFile\")'>";
				echo "<a href='?page=6&user=" . $user . "' onclick='event.stopPropagation()'>" . get_username($user) . "</a>";
				echo "<img src='$imageFile' class='homethumbnail'>";
				echo "<p class='caption'>" . $post['caption'] . "</p>";
				echo "</div>";
			}

			echo "</div>";
		} else {
			echo "<div id='getSomeFriends'>";
			echo "<h1 id='getStarted'>Get Started Now!</h1>";
			echo "<p>It seems that you are lonely. <a href='?page=4'>Make some friends!</a></p>";
			echo "</div>";
		}
	}
?>