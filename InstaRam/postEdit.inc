
<div id="editLightbox" class="hidden">


	<div id="postnavEdit">	
		<span id="xEdit" onclick="toggleEdit()">&times;</span><br>
	</div>

	<div id="boundaryBigImageEdit">
		<img id="contentEdit" src="" alt="">
	</div>
	<br>
	<form name="changeCaption" id="changeCaption" onsubmit="return editCaption()">
		<input type="textarea" name="postCaption" id="postCaption" value="">
		<button type="submit" id="saveEditPost">Save Changes</button>
	</form>

</div>