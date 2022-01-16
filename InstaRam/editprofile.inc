<?php
	$userInfo = get_user_data($_SESSION["userID"]);
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
   
	<br>Edit Profile<br><br>
	<label for="username">Username:</label>
	<span class="error">* <?php echo $usernameErr;?></span>
	<br>
	<input type="text" id="username" name="username" value="<?php echo $userInfo["username"];?>">
	<br><br>

	<label for="name">Name:</label>
	<span class="error">* <?php echo $nameErr;?></span>
	<br>
	<input type="text" id="name" name="name" value="<?php echo $userInfo["name"];?>">
	<br><br>
   
	<label>Connection to Mount Doug:</label>
	<span class="error">* <?php echo $connectionErr;?></span>
	<br>
	<input type="radio" id="current" name="connection" value="current" onclick="displayGrade()" <?php echo ($userInfo["connection"] == 'current') ? 'checked' : ''; ?>>
   
	<label for="current">Current Student</label><br>
	<input type="radio" id="alum" name="connection" value="alum" onclick="displayGrade()" <?php echo ($userInfo["connection"] == 'alum') ? 'checked' : ''; ?>>
   
	<label for="alum">Alumni</label>
	<label class="grade"><br><br>Current Grade:</label>
	<span class="error grade">* <?php echo $gradeErr;?></span><br>
   
	<select name="grade" class="grade" id="grade">
		<option value="" <?php echo $userInfo["grade"] == '' ? 'selected' : ''; ?>>Choose a Grade</option>
		<option value="9" <?php echo $userInfo["grade"] == '9' ? 'selected' : ''; ?>>Grade 9</option>
		<option value="10" <?php echo $userInfo["grade"] == '10' ? 'selected' : ''; ?>>Grade 10</option>
		<option value="11" <?php echo $userInfo["grade"] == '11' ? 'selected' : ''; ?>>Grade 11</option>
		<option value="12" <?php echo $userInfo["grade"] == '12' ? 'selected' : ''; ?>>Grade 12</option>
	</select>
	<br>
   
	<label for="bio">Enter Bio:</label>
	<span class="error"><?php echo $bioErr;?></span>
	<br>
	<textarea id="bio" name="bio" rows="4" cols="50"><?php echo $userInfo["bio"];?></textarea>
	<br><br>

	<br><br><input type="submit" value="Edit">
</form>