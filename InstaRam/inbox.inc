<?php
    echo "<h1 id='title'>Inbox</h1>";

    $user = get_user_data($_SESSION['userID']);
    $notifications = [];

    if (isset($notifications)) {
        echo "You have notifications.";
    }
?>