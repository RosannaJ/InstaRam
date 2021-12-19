<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Rambook</title>

		<!-- include an external style sheet -->
		<link rel="stylesheet" href="style.css">  

		<!-- include an external JavaScript file -->
		<script src="script.js"></script>
	</head>
  
	<body onload="displayGrade()">

    <a href="?page=1">Log In</a>
    <a href="?page=2">Create Account</a>

    <a href="?page=4">New Post</a>
    <a href='?action=del'>Reset Everything</a>

    <?php
	
			if (array_key_exists("username", $_SESSION) && $_SESSION['username'] !== null) {
				echo "Logged in as: " . $_SESSION['username'];
			} else {
				echo "Not Logged in.";
			}
		?>