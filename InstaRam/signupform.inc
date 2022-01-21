<div id="signupbox">

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data" name="form" onsubmit="signupHash()">

	<?php include "tos.inc";?>
   <br><br>

	<h1 class="title">Create Account</h1>
	
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
				<img src="images/upload.png" alt="upload image"><br>
				<label for="fileToUpload">Upload Profile Picture</label>
			</button>
			<span class="error"><?php echo $profileErr;?></span>
		</div>
	</div>
	<input type="file" id="fileToUpload" class="signupfile" name="fileToUpload"><br><br>
   
	<input type="checkbox" id="license" name="license" value="agree" <?php if ($license == "agree") echo "checked"; ?>>
	<label for="license">I agree to the </label><a id="tos" onclick="changeVisibility('termsofservice')">Terms of Service.</a>
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
		<option value="9" <?php echo $grade == '9' ? 'selected' : ''; ?>>Grade 9</option>
		<option value="10" <?php echo $grade == '10' ? 'selected' : ''; ?>>Grade 10</option>
		<option value="11" <?php echo $grade == '11' ? 'selected' : ''; ?>>Grade 11</option>
		<option value="12" <?php echo $grade == '12' ? 'selected' : ''; ?>>Grade 12</option>
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
   
	
	<span class="error"><?php echo $passwordErr;?></span><br>
	<input type="password" placeholder="Password (8 characters minimum)" class="loginsignupinfo" id="pw" name="pw"
		minlength="8" value="" required><br>
   
	<span class="error"></span><br>
	<input type="password" placeholder="Confirm Password" class="loginsignupinfo" id="pw2" name="pw2"
		minlength="8" value="" required>
<br><br>
		<button type="submit">Create Account</button><br><br>
	Already Have an Account? <a id="tologin" href="?page=1">Log In</a>
	 
</form>           

</div> 
