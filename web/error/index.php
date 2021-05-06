<?php
//include "/var/www/securitycenter.morgverd.com/main.php";
?>
<?php


if (!(isset($_GET['e']))) {
  $error = '500';
} else {
  $error = $_GET['e'];
}

$errInfo = array(
  "400" => array("Bad Request", "Your browser sent the incorrect data to us."),
  "401" => array("Unauthorised Access", "You do not have the required access to be there."),
  "403" => array("Forbidden", "You tried to access a page that you were not allowed to access."),
  "404" => array("Page not found", "The page you attempted to access could not be found. Did you miss-spell the URL?"),
  "500" => array("Internal Server Error", "The server has encountered an unexpected error."),
  "501" => array("Not Yet Implemented", "This webpage/feature has not yet been fully implemented."),
  "502" => array("Bad Gateway", "Cloudflare or our Apache Proxy system has pushed you through a bad gateway."),
  "503" => array("Service Unavaliable", "It appears that the server you tried to access is currently unavaliable.")
);


$errorTitle = "Unknown";
$errorDesc = "You have encountered an unexpected error.";
if (in_array($error, array_keys($errInfo))) {
  $errData = $errInfo[$error];
  $errorTitle = $errData[0];
  $errorDesc = $errData[1]; 
}


if ($_GET["f"] == "json") { header('Content-Type: application/json'); echo json_encode(array('success' => false, 'error_message' => $errorTitle), true); http_response_code(503); die(); };


$spanArr = array();
for ($i=0; $i < strlen($error); $i++) { array_push($spanArr, ("<span>".str_split($error)[$i]."</span>")); };
$spanStr = implode("", $spanArr);

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Error <?php echo $error;?></title>

        <!-- Google font -->
        <link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:900" rel="stylesheet">

        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style type="text/css">
            
            * {
              -webkit-box-sizing: border-box;
              box-sizing: border-box;
            }

            body {
              padding: 0;
              margin: 0;
            }

            #notfound {
              position: relative;
              height: 100vh;
            }

            #notfound .notfound {
              position: absolute;
              left: 50%;
              top: 50%;
              -webkit-transform: translate(-50%, -50%);
                  -ms-transform: translate(-50%, -50%);
                      transform: translate(-50%, -50%);
            }

            .notfound {
              max-width: 520px;
              width: 100%;
              line-height: 1.4;
              text-align: center;
            }

            .notfound .notfound-404 {
              position: relative;
              height: 240px;
            }

            .notfound .notfound-404 h1 {
              font-family: 'Montserrat', sans-serif;
              position: absolute;
              left: 50%;
              top: 50%;
              -webkit-transform: translate(-50%, -50%);
                  -ms-transform: translate(-50%, -50%);
                      transform: translate(-50%, -50%);
              font-size: 252px;
              font-weight: 900;
              margin: 0px;
              color: #262626;
              text-transform: uppercase;
              letter-spacing: -40px;
              margin-left: -20px;
            }

            .notfound .notfound-404 h1>span {
              text-shadow: -8px 0px 0px #fff;
            }

            .notfound .notfound-404 h3 {
              font-family: 'Cabin', sans-serif;
              position: relative;
              font-size: 16px;
              font-weight: 700;
              text-transform: uppercase;
              color: #262626;
              margin: 0px;
              letter-spacing: 3px;
              padding-left: 6px;
            }

            .notfound h2 {
              font-family: 'Cabin', sans-serif;
              font-size: 20px;
              font-weight: 400;
              text-transform: uppercase;
              color: #000;
              margin-top: 0px;
              margin-bottom: 25px;
            }

            .back-link a:link { color: #000; }
            .back-link a:visited { color: #000; }
            .back-link a:hover { color: #000; }
            .back-link a:active { color: #000; }

            @media only screen and (max-width: 767px) {
              .notfound .notfound-404 {
                height: 200px;
              }
              .notfound .notfound-404 h1 {
                font-size: 200px;
              }
            }

            @media only screen and (max-width: 480px) {
              .notfound .notfound-404 {
                height: 162px;
              }
              .notfound .notfound-404 h1 {
                font-size: 162px;
                height: 150px;
                line-height: 162px;
              }
              .notfound h2 {
                font-size: 16px;
              }
            }


        </style>

    </head>

    <body>

        <div id="notfound">
            <div class="notfound">
                <div class="notfound-404">
                    <h3>Oops! <?php echo $errorTitle?></h3>
                    <h1><?php echo $spanStr;?></h1>
                </div>
                <h2><?php echo $errorDesc;?></h2>
                <h3><a href="../" class="back-link">Back</a></h3>
            </div>
        </div>

    </body>

</html>
