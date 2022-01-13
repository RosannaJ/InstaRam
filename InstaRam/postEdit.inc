<!-- <div id="editPage" class="hidden">
</div> -->

<div id="editPage" class="hidden">
	<span id="x" onclick="closeEdit()">&times;</span><br>
	<span id="editpost" onclick="displayEdit()">Edit</span>

	<div id="boundaryBigImage">
		
		<!-- <a id="imageDownload" href="" download>Download Image</a><br> -->
		
		<img id="content" src="" alt="">
		
	</div>
	<textarea id="captionInput" name="caption" rows="4" cols="50"><?php echo $caption;?></textarea>
    <button type="button" onclick="savePost()">Save</button>

</div>