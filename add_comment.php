<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    include 'model/config.php';
    extract($_POST);
	$comment =addslashes(trim($comment));
    $insert_sql = "INSERT INTO `comment_section`( `ecode`, `ask_id`, `comment`) VALUES ('$ecode',$ask_id,'$comment')";
    $result = $conn->query($insert_sql);
    header("Location:index.php?action=classroom-discussion-single&ask_id=$ask_id");
?>