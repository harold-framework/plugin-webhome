<?php

if (isset($_GET["code"])) { header("Location: doLogin.php?code=".$_GET["code"]); die(); };

include_once("../config.php");
include_once("../themeSelector.php");
include_once("../authenticationManager.php");

$url = ($CONFIG["APIURL"]."/data");

$apiData = file_get_contents($url."?key=".$CONFIG["KEY"]."&page=QUOTES_ROOT");

if ($apiData == false) { header("Location: error/?e=503"); die(); }
$apiData = json_decode($apiData);
if (!($apiData->success)) { header("Location: error/?e=500"); die(); }


$themeSelector = new ThemeSelector( $apiData );
if (isset($_GET["change_theme"])) {
	$themeSelector->changeTheme();
	header( "Location: ." );
	die();
}
?>


<style>

  <?php
	if ($themeSelector->theme == "DARK") {
	?>

    * {
      --primary: #171717;
      --secondary: #ee0979;
      --tertiary: #171717;

      --text-title: #ffffff;
      --text-paragraph: #ffffff;

      --tag-background: #212529;

      --quotes-background: #212529;
      --quotes-background-secondary: #1a1d20;
      --quotes-box-shadow: #0e1012;
      --quotes-row-border: #212529;
      --quotes-text-color: #cfcfcf;
    }

    <?php
    } elseif ($themeSelector->theme == "LIGHT") {
    ?>

    * {
      --primary: #ff6a00;
      --secondary: #ee0979;
      --tertiary: #ffffff;

      --tag-background: #ffffff;
      --tag-text-colour: #52504d;

      --quotes-border: #d1d1d1;
      --quotes-background: #fafafa;
      --quotes-background-secondary: #ededed;
      --quotes-box-shadow: #dedede;
      --quotes-row-border: #fafafa;
      --quotes-text-color: #52504d;
      
    }

  <?php
  }
  ?>

</style>

<!DOCTYPE html>
<html lang="en">
<head>
  
  <?php
  if ($themeSelector->theme == "DARK") { echo '<link rel="stylesheet" href="https://raw.githubusercontent.com/sweetalert2/sweetalert2-themes/master/dark/dark.scss">'; };
  ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="og:type" content="website">
  <meta name="og:title" content="<?php echo $apiData->community_name;?>'s Quotes">
  <meta name="og:site_name" content="<?php echo $apiData->community_name;?>">
  
  <meta name="og:description" content="<?php echo $apiData->meta->description;?>">
  <meta name="og:image" content="<?php echo $apiData->meta->image;?>">
  

  <title>Login</title>
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
  <link href="../css/style.min.css?t<?php echo time();?>" rel="stylesheet">
</head>

<body>
  <header class="masthead text-center text-white" style="height: 100vh;">
    <div class="masthead-content" style="position: relative;">
      <div class="">
        <h1 class="masthead-heading mb-0">Oops!</h1>
        <h2 class="masthead-subheading mb-0">You must login to your Discord to continue!</h2>
        <a href="doLogin.php" class="btn btn-primary btn-xl rounded-pill mt-5">Login</a>

      </div>
    </div> 

    <?php
    for ($i=0; $i < $apiData->member_count; $i++) { 
    ?>
    <div class="bg-circle-<?php echo $i;?> bg-circle"></div>
    <?php
    }
    ?>
  </header>
</body>

</html>