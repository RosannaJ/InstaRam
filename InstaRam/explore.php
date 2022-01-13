<?php

	// go to login page if not logged in and on post form page
	if ((array_key_exists("page", $_GET) && $_GET["page"] == 4) && !array_key_exists("userID", $_SESSION)) {
		$_GET['page'] = 1;
	}

	include "profileLightbox.inc";
?>

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