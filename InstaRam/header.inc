<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Ramstagram</title>

		<!-- include an external style sheet -->
		<link rel="stylesheet" href="style.css">  
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" style>
		<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">


		<!-- include an external JavaScript file -->
		<script src="script.js"></script>
	</head>
  
	<body onload="displayGrade();filterProfiles();">
	<div id="top">
		<div id="header">
			<a href="?page=4" id='logolink'><h1 id="logo">Ramstagram</h1></a>

			<?php
				// temp

				// if (isset($_SESSION["userID"])) {
				// 	echo "Logged in as: " . get_username($_SESSION["userID"]);
				// } else {
				// 	echo "Not Logged In";
				// }

				if ($_SESSION["page"] > 2) {
					include "navbar.inc";
				}
			?>
		</div>
	</div>

	<div id="nohead">