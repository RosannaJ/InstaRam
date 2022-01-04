
<br>
<input type="text" id="searchBar" placeholder="Search.." onkeyup="filterProfiles()">
<br>

<select name="connectionFilter" id="connectionFilter" onchange="filterProfiles()">
  <option value="all">All</option>
  <option value="current">Student</option>
  <option value="alum">Alumni</option>
</select>

<br>
<div id="thumbnails"></div>

<?php

	// go to login page if not logged in and on post form page
	if ((array_key_exists("page", $_GET) && $_GET["page"] == 4) && !array_key_exists("username", $_SESSION)) {
		$_GET['page'] = 1;
	}

	include "profileLightbox.inc";

	/*echo "<br>";
	echo "<div id='thumbnails'>";
	foreach (get_all_user_data() as $user) {
		$profileImage = "users/" . $user['username'] . "/pfp." . $user['imageFileType'];
		echo "<div class='card' id='{$user['username']}' onclick='displayLightBox(\"$profileImage\")'>";
		echo "<img src='$profileImage' alt='{$user['username']}' class='thumbnail' width='180'>\n"; 		
		echo "<a href='?page=6&user={$user['username']}'>" . $user['username'] . "</a>";
		echo "<br>";
		echo "<p>" . $user['name'] . "</p>";
		echo "</div>";
	}
	echo "</div>";*/
	
	// // show uploaded images
	// if (is_dir($thumbnailDir)) {
	// 	$file = "";
	// 	$dh = opendir($thumbnailDir);
	// 	echo "<div id='thumbnails'>";
	// 	while (($file = readdir($dh)) !== false) {
	// 		if (!is_dir($file)) {
	// 			echo "<img src='$thumbnailDir$file' alt='$file' class='thumbnail' onclick='displayLightBox(\"$imageDir$file\")'>\n";
	// 		}
	// 	}
	// 	echo "</div>";
	// 	closedir($dh);
	// }
?>