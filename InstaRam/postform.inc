<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

   
   <br>New Post<br><br>

   <label for="fileToUpload">Image:</label>
   <span class="error">* <?php echo $fileErr;?></span>
   <br>
   <input type="file" id="fileToUpload" name="fileToUpload"><br><br>
   
   <label for="caption">Caption:</label>
   <span class="error"><?php echo $captionErr;?></span>
   <br>
   <textarea id="captionInput" maxlength="100" name="caption" rows="4" cols="50"><?php echo $caption;?></textarea>
   <br><br>
   
   <br><br><input type="submit" value="Post">
</form>           