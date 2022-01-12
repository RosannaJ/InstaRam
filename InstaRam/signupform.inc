<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data" name="form" onsubmit="hashPassword()">

	<?php include "tos.inc";?>
   
	<br>Create Account<br><br>
	<label for="username">Username:</label>
	<span class="error">* <?php echo $usernameErr;?></span>
	<br>
	<input type="text" id="username" name="username" value="<?php echo $username;?>">
	<br><br>

	<label for="name">Name:</label>
	<span class="error">* <?php echo $nameErr;?></span>
	<br>
	<input type="text" id="name" name="name" value="<?php echo $name;?>">
	<br><br>

	<label for="fileToUpload">Profile Pic:</label>
	<span class="error"><?php echo $profileErr;?></span>
	<br>
	<input type="file" id="fileToUpload" name="fileToUpload"><br><br>
   
	<input type="checkbox" id="license" name="license" value="agree" <?php if ($license == "agree") echo "checked"; ?>>
	<label for="license">I agree to the </label><a onclick="changeVisibility('termsofservice')">Terms of Service.</a>
	<span class="error">* <?php echo $licenseErr;?></span>
	<br><br>
   
	<label>Connection to Mount Doug:</label>
	<span class="error">* <?php echo $connectionErr;?></span>
	<br>
	<input type="radio" id="current" name="connection" value="current" onclick="displayGrade()" <?php echo ($connection == 'current') ? 'checked' : ''; ?>>
   
	<label for="current">Current Student</label><br>
	<input type="radio" id="alum" name="connection" value="alum" onclick="displayGrade()" <?php echo ($connection == 'alum') ? 'checked' : ''; ?>>
   
	<label for="alum">Alumni</label>
	<label class="grade"><br><br>Current Grade:</label>
	<span class="error grade">* <?php echo $gradeErr;?></span><br>
   
	<select name="grade" class="grade" id="grade">
		<option value="" <?php echo $grade == '' ? 'selected' : ''; ?>>Choose a Grade</option>
		<option value="g9" <?php echo $grade == 'g9' ? 'selected' : ''; ?>>Grade 9</option>
		<option value="g10" <?php echo $grade == 'g10' ? 'selected' : ''; ?>>Grade 10</option>
		<option value="g11" <?php echo $grade == 'g11' ? 'selected' : ''; ?>>Grade 11</option>
		<option value="g12" <?php echo $grade == 'g12' ? 'selected' : ''; ?>>Grade 12</option>
	</select>
	<br>
   
	<label for="bio">Enter Bio:</label>
	<span class="error"><?php echo $bioErr;?></span>
	<br>
	<textarea id="bio" name="bio" rows="4" cols="50"><?php echo $bio;?></textarea>
	<br><br>
   
	<label for="birthday">Enter Birthdate:</label><br>
	<input type="date" id="birthdate" name="birthdate" value="<?php echo $birthdate;?>" min="1900-01-01" max="2009-12-31">
	<span class="error">* <?php echo $birthdateErr;?></span>
	<br><br>
   
	<label for="password">Password (8 characters minimum):</label>
	<span class="error">* <?php echo $passwordErr;?></span><br>
	<input type="password" id="pw" name="pw"
		minlength="8" value="" required><br>
   
	<label for="password2">Confirm Password:</label>
	<span class="error">* </span><br>
	<input type="password" id="pw2" name="pw2"
		minlength="8" value="" required>

	<br><br><input type="submit" value="Create Account">
	<br><br>Already Have an Account? <a href="?page=1">Log In</a>
</form>           