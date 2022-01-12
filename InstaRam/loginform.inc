<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data" name="form" onsubmit="calcHash()">

    
   <div id="login" class="loginsignup">
      <div id="loginbox">
      <br>Log In<br>
         
      <label for="username">Username:</label>
      <span class="error">* <?php echo $loginUsernameErr;?></span>
      <br>
      
      <input type="text" id="username" name="username" value="<?php echo $loginUsername;?>">
      <br><br>

      <label for="password">Password:</label>
      <span class="error">* <?php echo $loginPasswordErr;?></span><br>
      <input type="password" id="pw" name="pw" value=""><br>
      <br><br>

      <input type="submit" value="submit"><br><br>
      Don't Have an Account? <a href="?page=2">Create Account</a>
      </div>
   </div>
</form>

