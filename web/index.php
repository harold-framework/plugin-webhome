<?php

include_once("config.php");
include_once("themeSelector.php");
include_once("authenticationManager.php");

$url = ($CONFIG["APIURL"]."/data");

$apiData = file_get_contents($url."?key=".$CONFIG["KEY"]."&page=ROOT&ukey=". getUserKey( ) );

if ($apiData == false) { header("Location: error/?e=503"); die(); }
$apiData = json_decode($apiData);
if (!($apiData->success)) { header("Location: error/?e=500"); die(); }

/*
  Substantiate a new ThemeSelector class,
  we pass the apiData element, this is so we
  can extract default theme from the plugin side
  configuration.
*/
$themeSelector = new ThemeSelector( $apiData );
if (isset($_GET["change_theme"])) {
  $themeSelector->changeTheme();
  header( "Location: ." );
  die();
}

$authenticationManager = new AuthenticationManager( $apiData, $CONFIG, "/" );
$userData = $authenticationManager->fetchUserData();

?>

<!--

  This is a plugin for the Harold Framework developed
  by MorgVerd and VinGal. It updates in real time, so if
  someone in the discord server changes their nickname, or
  their profile pictures, it will update on the next refresh of
  this page. We use the harold API extensively alongside a simple
  API plugin to serve the information we need.

  You can find the GitHub code here if you're interested in all the
  behind the scenes development, like the PHP managers and backend
  API code.

  https://github.com/harold-framework/plugin-webhome

-->

<!-- <?php echo time();?> -->

<!DOCTYPE html>
<style type="text/css">

  <?php
  if ($themeSelector->theme == "DARK") {
  ?>

  /* Dark Theme */
  * {
    --primary: #171717;
    --secondary: #ee0979;
    --tertiary: #171717;

    --text-title: #ffffff;
    --text-paragraph: #ffffff;

    --tag-background: #151515;
  }

  <?php
  } elseif ($themeSelector->theme == "LIGHT") {
  ?>

  /* Light Theme */
  * {
    --primary: #ff6a00;
    --secondary: #ee0979;
    --tertiary: #ffffff;

    --tag-background: #ffffff;
    --tag-text-colour: #52504d;
  }

  <?php
  }
  ?>

</style>  
<html lang="en">
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="og:type" content="website">
    <meta name="og:title" content="<?php echo $apiData->community_name;?>">
    <meta name="og:site_name" content="<?php echo $apiData->community_name;?>">
    <meta name="og:description" content="<?php echo $apiData->meta->description;?>">
    <meta name="og:image" content="<?php echo $apiData->meta->image;?>">
    <title><?php echo $apiData->community_name;?></title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link href="css/style.min.css?t<?php echo time();?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  </head>
  <body>
    <header class="masthead text-center text-white" style="height: 100vh;">
      <div class="masthead-content" style="position: relative;">
        <div class="container">
          <h1 class="masthead-heading mb-0"><?php echo $apiData->community_name;?></h1>
          <h2 class="masthead-subheading mb-0"><?php echo $apiData->community_tag;?></h2>
          <a href="<?php echo $apiData->button->link;?>" class="btn btn-primary btn-xl rounded-pill mt-5"><?php echo $apiData->button->name;?></a>
          <a href="#people">
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
    <?php
    $right = false;
    foreach ($apiData->people as $person) {
    $class_a = ""; $class_b = "";
    if ($right) { $class_a = " order-lg-2"; $class_b = " order-lg-1"; };
    $right = (!$right);
    ?>
    <section id="people">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6<?php echo $class_a;?>">
            <div class="p-5">
              <img class="img-fluid rounded-circle" src="<?php echo $person->avatar;?>" alt="Image not found" onerror="this.src = 'img/discord_avatar.jpg';" style="width: 100%; height: auto;" draggable="false">
            </div>
          </div>
          <div class="col-lg-6<?php echo $class_b;?>">
            <div class="p-5">
              <h2 class="display-4"><?php echo $person->name;?></h2>
              <p><?php echo $person->description;?></p>
            </div>
            <div>
              <ul class="disc-ul">
                  <?php
                  foreach($person->roles as $role) {
                  ?>
                    <li class="disc-li" style="border: 2px solid <?php echo $role->colour;?>">
                      <span class="disc-kelly" style="background-color: <?php echo $role->colour;?>"></span>
                      <div class="disc-text">
                        <span><?php echo $role->name;?></span>
                      </div>
                    </li>
                  <?php
                  }
                  ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php
    }
    ?>
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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="themeSelector.js"></script>
  </body>
</html>

<?php
if (isset($_GET["e"])) {
?>

<script type="text/javascript">
  
  Swal.fire({
    icon: 'error',
    title: '<a style="color: <?php if (($themeSelector->theme) == "DARK") { echo "#fff"; } else { echo "#282c34"; }; ?>">Error!</a>',
    html: '<a style="color: <?php if (($themeSelector->theme) == "DARK") { echo "#fff"; } else { echo "#282c34"; }; ?>"><?php echo $_GET["e"];?></a>',

    background: '<?php if (($themeSelector->theme) == "DARK") { echo "#282c34"; } else { echo "#fff"; }; ?>'
    
  })

</script>

<?php
}
?>