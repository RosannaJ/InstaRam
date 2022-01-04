<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Rambook</title>

		<!-- include an external style sheet -->
		<link rel="stylesheet" href="style.css">  
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

		<!-- include an external JavaScript file -->
		<script src="script.js"></script>
	</head>
  
	<body onload="displayGrade();filterProfiles();">

    <a href="?page=1">Log In</a>
    <a href="?page=2">Create Account</a>
	<a href="?page=7">New Post</a>
	<a href="?page=4">Explore</a>

	<a href='?action=del'>Reset Everything</a>
	<a href='?action=logout'>Log Out</a>

    <?php
	
		if (isset($_SESSION['username'])) {
			echo "Logged in as: " . $_SESSION['username'];
		} else {
			echo "Not Logged in.";
		}

		echo "<br>User Profiles: ";
		foreach (get_all_usernames() as $user) {
			echo "<a href='?page=6&user=$user'>$user</a> ";
		}
	?>