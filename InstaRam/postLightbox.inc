<?php 
	include "postEdit.inc";
?>
<div id="lightbox" class="hidden">
	<span id="prev" onclick="nextImage(-1)">&#10094;</span>
	<span id="next" onclick="nextImage(1)">&#10095;</span>
	
	<div id="postnav">
	<?php 
		if (array_key_exists('user', $_GET) && $_SESSION['userID'] == $_GET["user"]) {
			echo "<button type='button' id='editpost' class='editdelete' onclick='displayEdit()'>Edit</button>";
		}
		echo "<span id='x' onclick='closeLightBox()'>&times;</span>";
		if (array_key_exists('user', $_GET) && $_SESSION['userID'] == $_GET["user"]) {
			echo "<button type='button' id='deletepost' class='editdelete' onclick='deletePost()'>Delete</button>";
		}
	?>
	</div>
	

	<div id="boundaryBigImage">
		<img id="content" src="" alt="">
	</div>
	<br>
	<span class="material-icons" id="like" style="font-size:40px;" onclick="toggleLike()" >favorite_border</span>
	
	<p id="caption"></p>

	<form name="commentForm" id="commentform" onsubmit="return addComment()">
		<input type="text" name="text" id="text" placeholder="Type Comment..." maxlength="100">
		<button type="submit" id="addcomment" >Add Comment</button>
	</form>

	<div id="comments"></div>

</div>