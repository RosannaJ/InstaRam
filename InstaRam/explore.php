

<?php

	include "navbar.inc";
	include "explorepage.inc";

	// $jsonString = "";
	// $phpArray = "";
	
	// $thumbnailDir = "thumbnails/";
	// $imageDir = "profileimages/";

	// // if formDataFile exists, store previous data in array
	// if (file_exists($formDataFile)) {
	// 	$jsonString = file_get_contents($formDataFile);
	// 	$phpArray = json_decode($jsonString, true);
	// }

	// /*// show form data
	// echo "<pre>";
	// var_dump($phpArray);
	// echo "</pre>";
	// echo "\n";*/
	
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