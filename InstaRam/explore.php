<?php
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