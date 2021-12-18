<a href="downloadall.php" download>Download All</a><br>
<input type="text" id="searchBar" placeholder="Search.." onkeyup="filterImages()">
<br>

<select name="connectionFilter" id="connectionFilter" onchange="filterImages()">
  <option value="all">All</option>
  <option value="currentStudent">Student</option>
  <option value="alumni">Alumni</option>
  <option value="staff">Staff</option>
</select>

<div id="lightbox" class="hidden">
</div>

<div id="positionBigImage" class="hidden">
	<span id="prev" onclick="nextImage(-1)">&#10094;</span>
	<span id="next" onclick="nextImage(1)">&#10095;</span>

	<div id="boundaryBigImage">
		<span id="x" onclick="closeLightBox()">&times;</span><br>
		<a id="imageDownload" href="" download>Download Image</a><br>
		
		<img id="content" src="data:," alt="">
		
	</div>
	<p id="caption"></p>
</div>

<?php
	$jsonString = "";
	$phpArray = "";
	
	$thumbnailDir = "thumbnails/";
	$imageDir = "profileimages/";

	// if formDataFile exists, store previous data in array
	if (file_exists($formDataFile)) {
		$jsonString = file_get_contents($formDataFile);
		$phpArray = json_decode($jsonString, true);
	}

	/*// show form data
	echo "<pre>";
	var_dump($phpArray);
	echo "</pre>";
	echo "\n";*/
	
	// show uploaded images
	if (is_dir($thumbnailDir)) {
		$file = "";
		$dh = opendir($thumbnailDir);
		echo "<div id='thumbnails'>";
		while (($file = readdir($dh)) !== false) {
			if (!is_dir($file)) {
				echo "<img src='$thumbnailDir$file' alt='$file' class='thumbnail' onclick='displayLightBox(\"$imageDir$file\")'>\n";
			}
		}
		echo "</div>";
		closedir($dh);
	}
?>