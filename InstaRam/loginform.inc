<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

   <h1>InstaRam👍</h1>
       
   <br>Log In<br>
       
   <label for="username">Username:</label>
   <span class="error">* <?php echo $loginUsernameErr;?></span>
   <br>
   
   <input type="text" id="username" name="username" value="<?php echo $loginUsername;?>">
   <br><br>

   <label for="password">Password:</label>
   <span class="error">* <?php echo $loginPasswordErr;?></span><br>
   <input type="password" id="password" name="password" value=""><br>
   <br><br>

   <input type="submit" value="submit"><br><br>
   Don't Have an Account? <a href="?page=2">Create Account</a>
</form>

