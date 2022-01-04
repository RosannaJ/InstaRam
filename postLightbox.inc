<div id="lightbox" class="hidden">
</div>

<div id="positionBigImage" class="hidden">
	<span id="prev" onclick="nextImage(-1)">&#10094;</span>
	<span id="next" onclick="nextImage(1)">&#10095;</span>

	<div id="boundaryBigImage">
		<span id="x" onclick="closeLightBox()">&times;</span><br>
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