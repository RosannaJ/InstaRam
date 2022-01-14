<!-- <div id="lightbox" class="hidden">
</div> -->
<?php 
	if (isset($_GET["edit"])){
		include "postEdit.php";
	}
?>
<div id="lightbox" class="hidden">
	<span id="prev" onclick="nextImage(-1)">&#10094;</span>
	<span id="next" onclick="nextImage(1)">&#10095;</span>
	<span id="x" onclick="closeLightBox()">&times;</span><br>
	
	<?php 
		if ($_SESSION['userID'] == $_GET["user"]){
			echo "<span id='editpost' onclick='displayEdit()'>edit</span>";
			echo "<span id='deletePost' onclick='deletePost()'>delete</span>";
		}
	?>
	

	<div id="boundaryBigImage">
		
		<!-- <a id="imageDownload" href="" download>Download Image</a><br> -->
		
		<img id="content" src="" alt="">
		
	</div>
	<span class="material-icons" id="like" onclick="toggleLike()" >favorite_border</span>
	<p id="caption"></p>

	<form name="commentForm" onsubmit="return addComment()">
		<input type="text" name="text" id="text" value="">
		<input type="submit" value="Add Comment">
	</form>

	<div id="comments"></div>

</div>