<?php 
  session_start();

  if (isset($_SESSION['username'])) {
  	# database connection file
  	include 'app/db.conn.php';

  	include 'app/helpers/user.php';
  	include 'app/helpers/chat.php';
  	include 'app/helpers/opened.php';

  	include 'app/helpers/timeAgo.php';

  	if (!isset($_GET['user'])) {
  		header("Location: home.php");
  		exit;
  	}

  	# Getting User data data
  	$chatWith = getUser($_GET['user'], $conn);

  	if (empty($chatWith)) {
  		header("Location: home.php");
  		exit;
  	}

  	$chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);

  	opened($chatWith['user_id'], $conn, $chats);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat App</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<link rel="stylesheet" 
	      href="css/style.css">
	<link rel="icon" href="img/logo.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="d-flex
             justify-content-center
             align-items-center
             vh-100">
    <div class="w-400 shadow p-4 rounded">
    	<a href="home.php"
    	   class="fs-4 link-dark">&#8592;</a>

    	   <div class="d-flex align-items-center">
    	   	  <img src="uploads/<?=$chatWith['p_p']?>"
    	   	       class="w-15 rounded-circle">

               <h3 class="display-4 fs-sm m-2">
               	  <?=$chatWith['name']?> <br>
               	  <div class="d-flex
               	              align-items-center"
               	        title="online">
               	    <?php
                        if (last_seen($chatWith['last_seen']) == "Active") {
               	    ?>
               	        <div class="online"></div>
               	        <small class="d-block p-1">Online</small>
               	  	<?php }else{ ?>
               	         <small class="d-block p-1">
               	         	Last seen:
               	         	<?=last_seen($chatWith['last_seen'])?>
               	         </small>
               	  	<?php } ?>
               	  </div>
               </h3>
    	   </div>

    	   <div class="shadow p-4 rounded
    	               d-flex flex-column
    	               mt-2 chat-box"
    	        id="chatBox">
    	        <?php 
                     if (!empty($chats)) {
                     foreach($chats as $chat){
                     	if($chat['from_id'] == $_SESSION['user_id'])
                     	{ ?>
						<p class="rtext align-self-end
						        border rounded p-2 mb-1">
						    <?=$chat['message']?> 
						    <small class="d-block">
						    	<?=$chat['created_at']?>
						    </small>      	
						</p>
                    <?php }else{ ?>
					<p class="ltext border 
					         rounded p-2 mb-1">
					    <?=$chat['message']?> 
					    <small class="d-block">
					    	<?=$chat['created_at']?>
					    </small>      	
					</p>
                    <?php } 
                     }	