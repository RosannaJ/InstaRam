<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data" name="form" onsubmit="calcHash()">

    
   <div id="login" class="loginsignup">
      <div id="loginbox" class="logsign">
      <div id="table">
      <p class="title">Log In</p> <br>
      <span class="error"><?php echo $loginUsernameErr;?></span>
      <br>
      
      <input type="text" placeholder="Username" class="loginsignupinfo" id="username" name="username" value="<?php echo $loginUsername;?>">
      <br>

      <span class="error"><?php echo $loginPasswordErr;?></span><br>
      <input type="password" placeholder="Password" id="pw" class="loginsignupinfo" name="pw" value=""><br>
      <br>

      <button type="submit">Log In</button><br><br>
      Don't Have an Account? <a href="?page=2">Create Account</a>
      <br><br>
</div>  
   </div>
   </div>
</form>

