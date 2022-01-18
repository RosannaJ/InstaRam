<?php
	include "profileLightbox.inc";
?>

<div id="explorepage">
  <div id="explorebox">

<h1 id='title'>Explore</h1>


<input type="text" id="searchBar" placeholder="Search.." onkeyup="filterProfiles()">

<select name="connectionFilter" id="connectionFilter" class='homefilters' onchange="filterProfiles()">
  <option value="all">All Users</option>
  <option value="current">Student</option>
  <option value="alum">Alumni</option>
</select>

<select name="gradeFilter" id="gradeFilter" class='homefilters' onchange="filterProfiles()">
  <option value="all">All Grades</option>
  <option value="9">Grade 9</option>
  <option value="10">Grade 10</option>
  <option value="11">Grade 11</option>
  <option value="12">Grade 12</option>
</select>

<br><br><br>
<div id="thumbnails"></div>

</div>
</div>