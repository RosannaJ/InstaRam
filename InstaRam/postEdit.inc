<!-- <div id="editPage" class="hidden">
</div> -->

<div id="editPage" class="hidden">
	<span id="x" onclick="closeEdit()">&times;</span><br>
	<span id="editpost" onclick="displayEdit()">Edit</span>

	<div id="boundaryBigImage">
		
		<!-- <a id="imageDownload" href="" download>Download Image</a><br> -->
		
		<img id="content" src="" alt="">
		
	</div>
	<form name="changeCaption" onsubmit="return editCaption()">
		<input type="textarea" name="postCaption" id="postCaption" value="">
		<input type="submit" value="Save">
	</form>

</div>