<?php
ini_set('display_errors', 0); // Set to 1 in dev


function sanitize($value) {
	if ( !is_array($value) ) {
		// ignore if null. Nulls will be converted to empty string if not ignored.
		if ( is_null($value) ) {
			return $value;
		}

		// ignore if boolean. Booleans will be converted to empty string if not ignored.
		if ( is_bool($value) ) {
			return $value;
		}
		
		// trim leading and trailing spaces
		$value = trim($value);

		// If $value consists of several words, remove double spaces between words.
		while ( strpos($value, '  ') !== false ) {
			$value = str_replace('  ', ' ', $value);
		}
		return htmlspecialchars($value);
	} elseif ( is_assoc($value) ) {
		// Sanitize each element
		foreach ( $value as $k => $v ) {
			$value[$k] = sanitize($v);
		}
		return $value;
	} else {
		// Sanitize each element
		for ( $i = 0; $i < count($value); $i++ ) {
			$value[$i] = sanitize($value[$i]);
		}
		return $value;
	}
}

function highlight_in_string($haystack, $needle) {
  $i = 0;
  $needle_length = strlen($needle);
  $matches = [];
  while ( stripos($haystack, $needle, $i) !== false ) {
    $needlePos = stripos($haystack, $needle, $i);
    $matches[] = substr($haystack, $needlePos, $needle_length );
    $i = $needlePos + strlen($needle) + 1;
  }
  $unique_matches = array_unique($matches);

  foreach ( $unique_matches as $match ) {
    $haystack = str_replace($match, '<mark>' . $match . '</mark>', $haystack);
  }
  return $haystack;
}

if (!defined('API_URL')) define('API_URL', 'https://newsapi.org/v2/'); // INCLUDE TRAILING SLASH!!!!

$page = basename(__FILE__);
$site_url = 'http://localhost/news-client/' . $page;

$arr_countries = [];
$countries = file('countries.txt');

foreach($countries as $line) {
  $arr_line = explode(',', $line);
  $key = trim($arr_line[0]);
  $value = trim($arr_line[1]);
  $arr_countries[$key] = $value;
}

asort($arr_countries);

$country = 'us';
$search_keyword = '';

if ( isset($_POST['submit']) ) {
  $country = sanitize($_POST['country']);
  $search_keyword = sanitize($_POST['search_keyword']);
} elseif ( isset($_GET['country'] )) {
  $country = sanitize($_GET['country']);
  $search_keyword = isset($_GET['q']) ? sanitize($_GET['q']) : '';
}

$country_name = $arr_countries[$country];

$authorization = 'Authorization: Bearer <PASTE API KEY HERE>';
$cookie = 'Cookie: Authorization=<PASTE API KEY HERE>';

$url = API_URL . 'top-headlines';
$method = 'GET';
$data = compact('country');
if ( $search_keyword != '' ) {
  $data['q'] = $search_keyword;
}

$url = sprintf("%s?%s", $url, http_build_query($data));
$headers = array( $authorization, $cookie );

$curl = curl_init();

curl_setopt_array(
          $curl, 
          array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => $method,
              CURLOPT_HTTPHEADER => $headers,
			  CURLOPT_SSL_VERIFYHOST => 2,
              CURLOPT_SSL_VERIFYPEER => false   // set to true in prod env
          )
);

$response = curl_exec($curl);

if ( !curl_errno($curl) ) {
  $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
}

curl_close($curl);

$arr_response = json_decode($response, true);

if ( $http_code == 200 ) {
  $arr_articles = $arr_response['articles'];
  // Move articles with no image to Other news
  $carousel_news = [];
  $news = [];
  $other_news = [];
  foreach ( $arr_articles as $article ) {
    if ( $search_keyword != '' ) {
      $article['title'] = highlight_in_string($article['title'], $search_keyword);
      $article['description'] = highlight_in_string($article['description'], $search_keyword);
      $article['content'] = highlight_in_string($article['description'], $search_keyword);
    }
    if ( $article['urlToImage'] == '' ) {
      $other_news[] = $article;
    } else {
      $news[] = $article;
    }
  }
  $carousel_news = array_slice($news, 0, 3);
  $news = array_slice($news, 3);
  $carousel_news_count = count($carousel_news); 
} else {
  $error = $arr_response['message'];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <link rel="canonical" href="https://getbootstrap.com/docs/3.3/examples/offcanvas/">

    <title>Consume API Using PHP</title>

    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="dist/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/offcanvas.css" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
      img {
        max-width:100%;
      }
    </style>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="dist/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <nav class="navbar navbar-fixed-top navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <button   type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" 
            aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">World News</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a  href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" 
                  aria-haspopup="true" aria-expanded="false"><?php echo $country_name; ?><span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <?php 
                  foreach ( $arr_countries as $k => $v ) {
                    $data = ['country'=>$k];
                    if ( $search_keyword != '' ) {
                      $data['q'] = $search_keyword;
                    }
                    $url = $site_url;
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                ?>
                    <li><a href="<?php echo $url; ?>"><?php echo $v; ?></a></li>
                <?php
                  }
                ?>
              </ul>
            </li>
            <form class="navbar-form navbar-left" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="input-group">
                    <input type="hidden" class="form-control" name="country" value="<?php echo $country; ?>" />
                    <input type="text" class="form-control" name="search_keyword" 
                           value="<?php echo $search_keyword; ?>"
                           placeholder="Search" />
                    <span class="input-group-btn">
                        <button type="submit" name="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </form>
          </ul>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </nav><!-- /.navbar -->

    <div class="container">
      <?php
        if ( isset($error) ) {
      ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
          </div>
      <?php
        }
      ?>
      <div class="row row-offcanvas row-offcanvas-right">
        <div class="col-xs-12 col-sm-9">

          <?php if ( $carousel_news_count > 0 ) { ?>
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
              <!-- Indicators -->
              <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <?php if ( $carousel_news_count > 1 ) { ?>
                  <li data-target="#myCarousel" data-slide-to="1"></li>
                <?php } ?>
                <?php if ( $carousel_news_count > 2 ) { ?>
                  <li data-target="#myCarousel" data-slide-to="2"></li>
                <?php } ?>
              </ol>

              <!-- Wrapper for slides -->
              <div class="carousel-inner">

                <div class="item active">
                  <img src="<?php echo $carousel_news[0]['urlToImage']; ?>" style="width:100%;">
                  <div class="carousel-caption">
                    <h3><?php echo $carousel_news[0]['title']; ?></h3>
                    <a href="<?php echo $carousel_news[0]['url']; ?>"> Read more...</a>
                  </div>
                </div>

                <?php if ( $carousel_news_count > 1 ) { ?>
                  <div class="item">
                    <img src="<?php echo $carousel_news[1]['urlToImage']; ?>" style="width:100%;">
                    <div class="carousel-caption">
                      <h3><?php echo $carousel_news[1]['title']; ?></h3>
                      <a href="<?php echo $carousel_news[1]['url']; ?>"> Read more...</a>
                    </div>
                  </div>
                <?php } ?>

                <?php if ( $carousel_news_count > 2 ) { ?>  
                  <div class="item">
                    <img src="<?php echo $carousel_news[2]['urlToImage']; ?>" style="width:100%;">
                    <div class="carousel-caption">
                      <h3><?php echo $carousel_news[2]['title']; ?></h3>
                      <a href="<?php echo $carousel_news[2]['url']; ?>"> Read more...</a>
                    </div>
                  </div>
                <?php } ?>

              </div>

              <!-- Left and right controls -->
              <?php if ( $carousel_news_count > 1 ) { ?>  
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right"></span>
                  <span class="sr-only">Next</span>
                </a>
              <?php } ?>
            </div>
          <?php } ?>

          <div class="row">
            <?php 
              foreach ( $news as $article ) {
                $title = $article['title'];
                $arr_title = explode(' - ', $title);
                $publish_date = date('F n, Y g:i:s A', strtotime($article['publishedAt'])); 
            ?>
              <div class="col-xs-12 col-lg-6">
                <h3><?php echo $arr_title[0]; ?></h3>
                <?php
                  if ( isset($arr_title[1]) ) {
                ?>
                    <p>
                      <?php echo $arr_title[1]; ?> <span class="pull-right"><?php echo $publish_date; ?></span>
                    </p>
                <?php              
                  } else {
                ?>
                    <p><?php echo $publish_date; ?></p>
                <?php
                  }
                ?>
                <img class="news-img" src="<?php echo $article['urlToImage']; ?>" />
                <p>
                  <?php echo $article['description']; ?>
                  <a href="<?php echo $article['url']; ?>"> Read more...</a>
                </p>
              </div><!--/.col-xs-6.col-lg-4-->
            <?php 
              }
            ?>
          </div><!--/row-->

        </div><!--/.col-xs-12.col-sm-9-->

        <?php if ( count($other_news) > 0 ) { ?>
          <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
            <div class="list-group">
              <a href="#" class="list-group-item active">Other News</a>
              <?php 
                foreach ( $other_news as $article ) {
              ?>
                  <a href="<?php echo $article['url']; ?>" class="list-group-item">
                    <?php echo $article['title']; ?>
                  </a>
              <?php 
                }
              ?>
            </div>
          </div><!--/.sidebar-offcanvas-->
        <?php } ?>
      </div><!--/row-->

      <hr>

      <footer>
        <div class="center">
          <p><a href="https://newsapi.org" target="_blank">Powered by News API</a></p>
        </div>
      </footer>

      <?php
      /*
        echo '<pre>';
        print_r($arr_articles);
        echo '</pre>';
      */
      ?>
    </div><!--/.container-->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="dist/assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="dist/assets/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="dist/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/offcanvas.js"></script>
  </body>
</html>
