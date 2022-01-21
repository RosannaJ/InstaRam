<div class="topnav" id="myTopnav">
<a href="?page=4" class='active' id='logolink'><h1 id="logo">Ramstagram</h1></a>
<?php
	if ($_SESSION["page"] > 2) {
?>
  <div id="links">
  <a href="?page=3" class='navlinks'><img src="images/home.png" alt="home"></a>
  <a href="?page=4" class='navlinks'><img src="images/explore.png" alt="explore"></a>
  <a href="?page=5" class='navlinks'><img src="images/inbox.png" alt="inbox"><span id="unreadAmount"></span></a>
  
  <a href="?page=7" class='navlinks'><img src="images/newpost.png" alt="new post"></a>
  <a href="?page=6&user=<?php echo $_SESSION["userID"] ?>" class='navlinks'><img src="images/profile.png" alt="profile"></a>
	</div>
<?php	
    }
?>

</div>