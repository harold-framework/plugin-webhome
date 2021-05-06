<?php

include_once("../config.php");
include_once("../themeSelector.php");
include_once("../authenticationManager.php");

$url = ($CONFIG["APIURL"]."/data");

$apiData = file_get_contents($url."?key=".$CONFIG["KEY"]."&page=QUOTES_ROOT");

if ($apiData == false) { header("Location: error/?e=503"); die(); }
$apiData = json_decode($apiData);
if (!($apiData->success)) { header("Location: error/?e=500"); die(); }

$authenticationManager = new AuthenticationManager( $apiData, $CONFIG, "/quotes" );
$authenticationManager->securePage();

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
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="og:type" content="website">
  <meta name="og:title" content="<?php echo $apiData->community_name;?>'s Quotes">
  <meta name="og:site_name" content="<?php echo $apiData->community_name;?>">
  
  <meta name="og:description" content="<?php echo $apiData->meta->description;?>">
  <meta name="og:image" content="<?php echo $apiData->meta->image;?>">
  

  <title><?php echo $apiData->community_name;?>'s Quotes</title>
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
  <link href="../css/style.min.css?t<?php echo time();?>" rel="stylesheet">
</head>

<body>
  <header class="masthead text-center text-white" style="height: 100vh;">
    <div class="masthead-content" style="position: relative;">
      <div class="">
        <h1 class="masthead-heading mb-0"><?php echo $apiData->community_name;?> Quotes</h1>
        <h2 class="masthead-subheading mb-0"><?php echo $apiData->community_tag;?></h2>
        <a href="<?php echo $apiData->button->link;?>" class="btn btn-primary btn-xl rounded-pill mt-5"><?php echo $apiData->button->name;?></a>

        <a href="#quotes">
          <div class="scroll-down">
            <div class="scroll-down-holder">
              <div class="chevron"></div>
              <div class="chevron"></div>
              <div class="chevron"></div>
            </div>
          </div>
        </a>
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

  <br><br>
  <section id="quotes">
    <div class="container">
      <div class="di-m1">
        <table>
            <tr class="di-he">
              <th class="di-he-pfp"></th>
              <th class="di-he-user di-tcleft di-he-text">User</th>
              <th class="di-he-quote di-he-text" >Quote Total</th>
              <th class="di-he-recent di-he-text">Recent Quote</th>
            </tr>
          <tr class="di-tc" onclick="CHANGEME()">
            <th class="di-tcright">
              <span>
                <img class="di-tc-img img-fluid rounded-circle" src="{USER IMG PLACEHOLDER}"/>
              </span>
            </th>
            <th class="di-tcleft di-tc-text">{USERNAME PLACEHOLDER}</th>
            <th class="di-tc-text">{TOTALQUOTE PLACEHOLDER}</th>
            <th class="di-tc-text">{RECENTQUOTE PLACEHOLDER}</th>
          </tr>
        </table>
      </div>
    </div>
  </section>
    <footer class="py-5 bg-black" style="background: #2C2F33;">
      <div class="container">
        <p class="m-0 text-center text-white small">Copyright &copy; <script>document.write(new Date().getFullYear())</script> <?php echo $apiData->community_name;?></p>

        <a href="?change_theme">
          <p class="m-0 text-center text-white small">

          Swap to <?php echo ucfirst( strtolower( $themeSelector->getInverseTheme() ) ); ?> Theme
          </p>
        </a>

      </div>
    </footer>
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>