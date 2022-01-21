<?php 
	
	include "postLightbox.inc";

	echo "<div id='homebox'>";
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
		}// if

		// add own posts
		$allPosts = array_merge($allPosts, get_posts_separated([$_SESSION["userID"]]));

		// display posts
		if (count($allPosts) > 0) {
			
			// sort posts by UID
			usort($allPosts, "cmp_UID");

			echo "<div id='thumbnails'>";

			// loop through all posts
			foreach ($allPosts as $post) {
				$user = get_user_of_post($post['UID']);
				$userInfo = get_user_data($user);
				$imageFile = "users/$user/posts/" . $post['UID'] . "." . $post['imageFileType'];
				$pfpImageFile = "";

				if ($userInfo["imageFileType"] == "") {
					$pfpImageFile = "images/defaultpfp.jpg";
				} else {
					$pfpImageFile = "users/$user/pfp." . $userInfo["imageFileType"];
				}

				// display username, image, and caption for each post
				echo "<div class='card' id='{$post['UID']}' onclick='displayLightBox(\"$imageFile\")'>";

				echo "<a id='usernameonhome' href='?page=6&user=" . $user . "' onclick='event.stopPropagation()'>";
				echo "<img src='$pfpImageFile' class='thumbnailpfp'>";
				echo get_username($user);
				echo "</a>";

				echo "<img src='$imageFile' class='homethumbnail'>";
				echo "<p class='caption'>" . $post['caption'] . "</p>";
				echo "</div>";
			} // foreach

			echo "</div>";
		} else {
			echo "<div id='getSomeFriends'>";
			echo "<p>It seems that you are lonely. <a id='makefriends' href='?page=4'>Make some friends!</a></p>";
			echo "</div>";
		} // else
	}// if
	echo "<div>";
?>