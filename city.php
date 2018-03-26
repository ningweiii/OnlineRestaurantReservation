<!DOCTYPE html>
<?php
// Start the session
if (!isset($_SESSION)) session_start();
?>
<html>

<head>
  <!-- BASICS -->
  <meta charset="utf-8">
  <title>Online Resturant Reservation</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="js/rs-plugin/css/settings.css" media="screen">
  <link rel="stylesheet" type="text/css" href="css/isotope.css" media="screen">
  <link rel="stylesheet" href="css/flexslider.css" type="text/css">
  <link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400italic,700|Open+Sans:300,400,600,700">
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <!-- skin -->
  <link rel="stylesheet" href="skin/default.css">

<!-- php -->
<?php 
include 'includes/functions.php';
include 'includes/classRestaurant.php';
require_once 'includes/classDatabase.php';

?>

</head>

<body>
  <section id="header" class="appear"></section>
  <div class="navbar navbar-fixed-top" role="navigation" data-0="line-height:100px; height:100px; background-color:rgba(0,0,0,0.3);" data-300="line-height:60px; height:60px; background-color:rgba(5, 42, 62, 1);">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      	  <span class="fa fa-bars color-white"></span>
        </button>
        <div class="navbar-logo">
          <a href="index.php"><img data-0="width:155px;" data-300=" width:120px;" src="img/logo.png" alt=""></a>
        </div>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav" data-0="margin-top:20px;" data-300="margin-top:5px;">

          <li class="active"><a href="index.php">Home</a></li>
          <li ><a href="login.php">Sign In</a></li>
          <li><a href="register.php">Sign Up</a></li>
          <li><a href="#section-contact">en</a></li>
        </ul>
      </div>
      <!--/.navbar-collapse -->
    </div>
  </div>

  <section id="intro" style="height:20vh">
    <div class="intro-content">
    </div>
  </section>
  
<!-- search -->
  <section id="search" class="appear">
  <form action="" method="post"> 
  <div class="row">
    <div class="col-xs-6">
      <div class="input-group">
      <input name="txtSearch" type="text" class="form-control" placeholder="Search" id="txtSearch"/>
      <select name='searchMethod'>
      <option value = "0" > by name </option>
      <option value = "1"> by category </option>
    </select>
   <div class="input-group-btn">
        <button name="search" class="btn btn-primary" type="submit">
        <span class="glyphicon glyphicon-search"></span>
        </button>
   </div>
   </div>
    </div>
  </div>
</form>
</section>

<?php

  if(isset($_POST['search']))
  {
    $txtSearch=$_POST["txtSearch"];
		$dataBase=new Database("restaurant_reservation_db");
		$connection=$dataBase->GetConnection();
    // echo $_POST['searchMethod'];
    // Database::CloseConnection();
    $selected = "0";
    if(isset($_POST['searchMethod']))
    {
      
      $selected = $_POST['searchMethod'];  
      switch($selected)
      {
        case '0':    
        $array= Restaurant::GetRestaurantByName($txtSearch,$connection); 
        // echo count($array);
        if(count($array)>0)
        {
          $_SESSION['searchedRestaurant'] = $array;
          // print_r($_SESSION['searchedRestaurant'] );
          header("Location:category.php");
        }
        else
        {
          echo "nothing found!";
        }
              break;
        case '1': 
        $array= Restaurant::GetRestaurantByCategory($txtSearch,$connection);
        // echo count($array);
        // foreach($array as $value)
        // {
        //   print_r ($value);
        // }
        if(count($array)>0)
        {
          // print_r ($array);
          $_SESSION['searchedRestaurant'] = $array;
          print_r($_SESSION['searchedRestaurant'] );
          header("Location:category.php");
        }
        else
        {
          echo "nothing found!";
        }
              break;
      }
    }

  }
?>

  <!-- about -->
  <section id="section-about" class="section appear clearfix">
    <div class="container">

      <div class="row mar-bot40">
        <div class="col-md-offset-3 col-md-6">
          <div class="section-header">
            <h2 class="section-heading animated" data-animation="bounceInUp">Different cuisine in <?php echo $_GET['chosenCity']?> </h2>
          </div>
        </div>
      </div>

      <div class="row align-center mar-bot40">
        <?php
        displayAllCuisines();          


        ?>
      </div>

    </div>
  </section>
  <!-- /about -->


  <section id="footer" class="section footer">
    <div class="container">
      <div class="row animated opacity mar-bot20" data-andown="fadeIn" data-animation="animation">
        <div class="col-sm-12 align-center">
          <ul class="social-network social-circle">
            <li><a href="#" class="icoRss" title="Rss"><i class="fa fa-rss"></i></a></li>
            <li><a href="#" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
            <li><a href="#" class="icoTwitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>
            <li><a href="#" class="icoGoogle" title="Google +"><i class="fa fa-google-plus"></i></a></li>
            <li><a href="#" class="icoLinkedin" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="row align-center mar-bot20">
        <ul class="footer-menu">
          <li><a href="#">Home</a></li>
          <li><a href="#">About us</a></li>
          <li><a href="#">Privacy policy</a></li>
          <li><a href="#">Get in touch</a></li>
        </ul>
      </div>
      <div class="row align-center copyright">
        <div class="col-sm-12">
          <p>Copyright &copy; All rights reserved</p>
        </div>
      </div>
      <div class="credits">
        <!--
          All the links in the footer should remain intact.
          You can delete the links only if you purchased the pro version.
          Licensing information: https://bootstrapmade.com/license/
          Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Vlava
        -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade.com</a>
      </div>
    </div>

  </section>
  <a href="#header" class="scrollup"><i class="fa fa-chevron-up"></i></a>

  <!-- Javascript Library Files -->
  <script src="js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.isotope.min.js"></script>
  <script src="js/jquery.nicescroll.min.js"></script>
  <script src="js/fancybox/jquery.fancybox.pack.js"></script>
  <script src="js/skrollr.min.js"></script>
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.localScroll.min.js"></script>
  <script src="js/stellar.js"></script>
  <script src="js/jquery.appear.js"></script>
  <script src="js/jquery.flexslider-min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8HeI8o-c1NppZA-92oYlXakhDPYR7XMY"></script>

  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>

</body>

</html>
