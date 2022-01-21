<div id="inboxpage">
<div id="inboxbox">
	<h1 id='title'>Inbox</h1>
	
	<input type="checkbox" name="notifSelectAll" id="notifSelectAll" onclick="toggleAllNotifs();"></input>
	<label for="notifSelectAll" id="labeSelectAll">Select All</label>
	
	<div id="inboxbutton">
		<!--<button id="deleteAllNotifs" class="hidden" onclick="deleteAllNotifs();">Delete All</button>-->
		<button id="deleteNotifs" onclick="editNotifs('delete')">Delete</button>
		<button id="unreadNotifs" onclick="editNotifs('unread')">Mark as Unread</button>
		<button id="readNotifs" onclick="editNotifs('read')">Mark as Read</button>
	</div><br><br>
	<div id="notifications"></div>
</div>
</div>