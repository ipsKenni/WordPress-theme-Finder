<?php
  $pageURL = htmlspecialchars($_POST["pageURL"]);
  function getTheme($pageURL = false) {
    if ($pageURL) {

        $reSecret = "Write here your private recaptcha key";
        $reResponse = null;
        $reCaptcha = new ReCaptcha($reSecret);

        if (htmlspecialchars($_POST["g-recaptcha-response"])) {
          $response = $reCaptcha->verifyResponse(
              $_SERVER["REMOTE_ADDR"],
              htmlspecialchars($_POST["g-recaptcha-response"])
          );
        }

      if ($response != null && $response->success) {
        if (strpos($pageURL, 'http') === false) {
          $pageURL = 'http://' . $pageURL . '/';
        }
        
        $pageQuell = htmlspecialchars(file_get_contents($pageURL));

        if ($pageQuell === "") {
          $pageURL = substr($pageURL, 4);
          $pageURL = 'https' . $pageURL;
          $pageQuell = htmlspecialchars(file_get_contents($pageURL));
        }

        $pageCut = stristr( $pageQuell, '/wp-content/themes/');
        $pageExport3 = explode('/', $pageCut);
        if ($pageExport3[3] != '') {
          echo '<br /><div class="success"><h2>Theme name: ' . $pageExport3[3] . '</h2><h5><a href="http://lmgtfy.com/?q=Wordpress Theme ' . $pageExport3[3] . '">Where do I get the theme from?</a></h5></div>';
        } else {
          echo '<br /><div class="error"><h5>Error:<br /> Theme could not be found or the page does not use Wordpress</h5></div>';
        }
      } else {
        echo '<br /><div class="error"><h5>Error:<br /> ReCaptcha is not right try again</h5></div>';
      }
    }
  }

?>
<!doctype html>

<html lang="de">
<head>
  <meta charset="utf-8">
  <title>Wordpress Theme Finder</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Mono" rel="stylesheet">
  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<div class="content">
  <form action="" method="post">
  <p>Website Url: </p><input type="text" size="50" value="<?php echo $pageURL; ?>" name="pageURL"> 
  <input type="submit" value="Submit" name="submitbutton">
  <center><div class="g-recaptcha" data-sitekey="Write here your public recaptcha key"></div></center>
  <p>Example: http://example.com/</p>
  </form>
  
  <?php
    getTheme($pageURL);
  ?>
  <br />
</div>
</body>
</html>
