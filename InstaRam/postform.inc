<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

<div id="newpost" class="loginsignup">
      <div id="newpostbox" class="logsign">
      <div id="table">
      <p class="title">New Post</p> <br>
      <span class="error"><?php echo $fileErr;?></span><br>
   <div id="wrapper">
		<div id="hidefile">
		</div>
		<div id="profile">
   
			<button id="profilepicinput">
				<img src="images/upload.png" src="upload image"><br>
				<label for="fileToUpload">Upload Image</label>
			</button>
			
		</div>
	</div>
	<input type="file" id="fileToUpload" name="fileToUpload"><br><br>


   <!-- <label for="fileToUpload">Image:</label>
   <span class="error">* <?php echo $fileErr;?></span>
   <br>
   <input type="file" id="fileToUpload" name="fileToUpload"><br><br> -->
   
   <label for="caption">Caption:</label>
   <span class="error"><?php echo $captionErr;?></span>
   <br>
   <textarea id="captionInput" maxlength="100" name="caption" rows="3" cols="30"><?php echo $caption;?></textarea>
   <br>
   <br><br><button type="submit">Post</button><br><br>
</div>
</div>
</div>
</form>           