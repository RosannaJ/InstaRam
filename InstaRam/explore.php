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

<select name="gradeFilter" id="gradeFilter" onchange="filterProfiles()">
  <option value="all">All Grades</option>
  <option value="9">Grade 9</option>
  <option value="10">Grade 10</option>
  <option value="11">Grade 11</option>
  <option value="12">Grade 12</option>
</select>

<br>
<div id="thumbnails"></div>