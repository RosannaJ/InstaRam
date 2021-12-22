<div id="lightbox" class="hidden">
</div>

<div id="positionBigImage" class="hidden">
	<span id="prev" onclick="nextImage(-1)">&#10094;</span>
	<span id="next" onclick="nextImage(1)">&#10095;</span>

	<div id="boundaryBigImage">
		<span id="x" onclick="closeLightBox()">&times;</span><br>
		<!-- <a id="imageDownload" href="" download>Download Image</a><br> -->
		
		<img id="content" src="" alt="">
		
	</div>
	<p id="caption"></p>
</div>

<?php
	if (array_key_exists("user", $_GET) && username_exists($_GET["user"], get_all_usernames())) {

		// loop through all users to find current one
		foreach (get_all_user_data() as $user) {

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

				// display posts
				if (is_file($folderName . "posts.json")) {
					
					//$postsArray = json_decode(file_get_contents($folderName . "posts.json"), true);

					echo "<div id='thumbnails'>\n";

					// loop through "posts" folder
					if (is_dir($folderName)) {
						$dh = opendir($folderName);
						while (($file = readdir($dh)) !== false) {
							$imageFileType = mime_content_type("users/{$user['username']}/posts/$file");
							$fileNum = explode(".", $file)[0];

							// display if it is an image
							if (strcmp($imageFileType, "image/jpg") === 0 || strcmp($imageFileType, "image/png") === 0 || strcmp($imageFileType, "image/jpeg") === 0) {
								
								// display image
								echo "<img src='$folderName$file' width='240' class='thumbnail' onclick='displayLightBox(\"$folderName$file\")'>\n";
								//echo "<br>Caption: " . $postsArray[$fileNum]["caption"] . "<br>";
							}
						}
						closedir($dh);
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