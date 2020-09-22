<?php 
  session_start(); 

  if (!isset($_SESSION['firstname'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['firstname']);
  	header("location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
	<h2>TVNET RSS latest news feed</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['firstname'])) : 
      

        $url = "https://tvnet.lv/rss";
        $xml = simplexml_load_file($url) or die("Error: Cannot create object");;
        
        $i = 0;
        if(!empty($xml)) {

          foreach ($xml->channel->item as $item) {
            $img = $item->enclosure['url'];
            $title = $item->title;
            $description = $item->description;
            $link = $item->link;

            if($i>=5) break;
            ?>
            <div class="news-post">
              <img src="<?php echo $img; ?>">
              <h3><?php echo $title; ?></h3>
              <p><?php echo $description; ?></p>
              <a href="<?php echo $link; ?>">link to article</a>
            </div>

            <?php
            $i++;
          }
        }else{
          echo "<h2>No items found</h2>";
        }
        ?>
      


      

    	<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
</div>
		
</body>
</html>