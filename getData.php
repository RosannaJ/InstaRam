<?php
	session_start();
	include "functions.php";

	if (array_key_exists("UID", $_GET) && $_SESSION["page"] == 6) { // is page check needed?
		
		// find post with requested uid and var_dump
		foreach (get_all_posts() as $user) {
			foreach ($user as $post) {
				if ($post["UID"] == $_GET["UID"]) {
					echo json_encode($post, JSON_PRETTY_PRINT);

					// opt: add username in var_dump?

					break 2;
				}
			}
		}
	}
?>