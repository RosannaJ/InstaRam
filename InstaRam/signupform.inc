<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data" name="form" onsubmit="hashPassword()">

	<?php include "tos.inc";?>
   
	<div id="signup" class="loginsignup">
      <div id="signupbox" class="logsign">
      <div id="table">

	<p class=title>Create Account</p>
	
	<span class="error"><?php echo $usernameErr;?></span>
	<br>
	<input type="text" placeholder="Username" id="username" class="loginsignupinfo" name="username" value="<?php echo $username;?>">
	<br>

	<span class="error"><?php echo $nameErr;?></span>
	<br>
	<input type="text" placeholder="Name" id="name" class="loginsignupinfo" name="name" value="<?php echo $name;?>">
	<br><br>

	<div id="wrapper">
		<div id="hidefile">
			<!-- <input type="file" id="fileToUpload" name="fileToUpload"> -->
		</div>
		<div id="profile">
			<button id="profilepicinput">
				<img src="images/upload.png" src="upload image"><br>
				<label for="fileToUpload">Upload Profile Picture</label>
			</button>
			<span class="error"><?php echo $profileErr;?></span>
		</div>
	</div>
	<input type="file" id="fileToUpload" name="fileToUpload"><br><br>
   
	<input type="checkbox" id="license" name="license" value="agree" <?php if ($license == "agree") echo "checked"; ?>>
	<label for="license">I agree to the </label><a onclick="changeVisibility('termsofservice')">Terms of Service.</a>
	<span class="error">* <?php echo $licenseErr;?></span>
	<br><br>
   
	<label>Connection to Mount Doug:</label>
	<span class="error">* <?php echo $connectionErr;?></span>
	<span class="error"><?php echo $gradeErr;?></span>
	<br>
	<input type="radio" id="current" name="connection" value="current" onclick="displayGrade()" <?php echo ($connection == 'current') ? 'checked' : ''; ?>>
   
	<label for="current">Current Student</label>
	
	<label class="grade"><br>Current Grade:</label>
	<select name="grade" class="grade" id="grade">
		<option value="" <?php echo $grade == '' ? 'selected' : ''; ?>>Choose a Grade</option>
		<option value="g9" <?php echo $grade == 'g9' ? 'selected' : ''; ?>>Grade 9</option>
		<option value="g10" <?php echo $grade == 'g10' ? 'selected' : ''; ?>>Grade 10</option>
		<option value="g11" <?php echo $grade == 'g11' ? 'selected' : ''; ?>>Grade 11</option>
		<option value="g12" <?php echo $grade == 'g12' ? 'selected' : ''; ?>>Grade 12</option>
	</select>
	
	
	<br>
	<input type="radio" id="alum" name="connection" value="alum" onclick="displayGrade()" <?php echo ($connection == 'alum') ? 'checked' : ''; ?>>
	<label for="alum">Alumni</label>
	
   <br><br>
	<label for="bio">Enter Bio:</label>
	<span class="error"><?php echo $bioErr;?></span>
	<br>
	<textarea maxlength="180" id="bio" name="bio" rows="3" cols="30"><?php echo $bio;?></textarea>
	<br><br>
   
	<label for="birthday">Enter Birthdate:</label>
	<span class="error">* <?php echo $birthdateErr;?></span><br>
	<input type="date" id="birthdate" name="birthdate" value="<?php echo $birthdate;?>" min="1900-01-01" max="2009-12-31">
	<br><br>
   
	
	<span class="error"><?php echo $passwordErr;?></span><br>
	<input type="password" placeholder="Password (8 characters minimum)" class="loginsignupinfo" id="pw" name="pw"
		minlength="8" value="" required><br>
   
	<span class="error"></span><br>
	<input type="password" placeholder="Confirm Password" class="loginsignupinfo" id="pw2" name="pw2"
		minlength="8" value="" required>
<br><br>
		<button type="submit">Create Account</button><br><br>
	Already Have an Account? <a href="?page=1">Log In</a> <br><br>
	</div>  
   </div>
   </div>
</form>           