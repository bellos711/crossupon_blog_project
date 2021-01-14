<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<!-- FETCH existing DATA -->
<?php
$SearchQueryParameter = $_GET["username"];
global $ConnectingDB;
$sql = "SELECT aname,aheadline,abio,aimage FROM admins WHERE username=:userName";
$stmt = $ConnectingDB->prepare($sql);
$stmt->bindValue(':userName', $SearchQueryParameter);
$stmt->execute();
$Result = $stmt->rowcount();
if($Result==1){
  while ($DataRows=$stmt->fetch()) {
    $ExistingName = $DataRows["aname"];
    $ExistingBio = $DataRows["abio"];
    $ExistingImage = $DataRows["aimage"];
    $ExistingHeadline = $DataRows["aheadline"];
  }//endwhile
}//endif
else {
  $_SESSION["ErrorMessage"]="Bad Request!";
  Redirect_to("Blog.php?page=1");
}//endelse
?>
<!DOCTYPE html>
<html lang="en" >
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="Css/styles.css">
    <title>Profile</title>
  </head>
  <body>
    <!-- NAVBAR -->
    <div style="height:10px; background:#1F618D;"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container" >
        <a href="Blog.php?page=1" class="navbar-brand"> CROSSUPON.COM</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarcollapseCMS">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a href="Blog.php?page=1" class="nav-link">Home</a>
          </li>
          <li class="nav-item">
            <a href="About.php" class="nav-link">About Us</a>
          </li>
          <li class="nav-item">
            <a href="Blog.php?page=1" class="nav-link">Blog</a>
          </li>
          <li class="nav-item">
            <a href="ContactUs.php" class="nav-link">Contact Us</a>
          </li>
          <li class="nav-item">
            <a href="Features.php" class="nav-link">Features</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <form class="form-inline d-none d-sm-block" action="Blog.php?page=1" >
            <div class="form-group">
            <input class="form-control mr-2" type="text" name="Search" placeholder="Search Here.." value="">
            <button  class="btn btn-primary" name="SearchButton">Go</button>
            </div>
          </form>
        </ul>
        </div>
      </div>
    </nav>
      <div style="height:10px; background:#1F618D;"></div>
      <!-- NAVBAR END -->
      <!-- HEADER -->
      <header class="bg-dark text-white py-3">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
            <h1><i class="fas fa-user mr-2" style="color: orange;"></i> <?php echo $ExistingName; ?></h1>
            <h3><?php echo $ExistingHeadline; ?></h3>
            <p></p>
            </div>
          </div>
        </div>
      </header>
      <!-- HEADER END -->


      <section class="container py-2 mb-4">
        <div class="row">
            <div class="col-md-3">
              <img src="images/<?php echo $ExistingImage; ?>" class="d-block img-fluid mb-3 rounded-circle" alt="">
            </div>
            <div class="col-md-9" style="min-height: 400px;">
              <div class="card">
                  <div class="card-body">
                    <p class="lead"> <?php echo $ExistingBio; ?></p>
                  </div>
              </div>
            </div>
        </div>
      </section>
      <!-- FOOTER -->
      <footer class="bg-dark text-white">
        <div class="container">
          <div class="row">
            <div class="col">
              <p class="lead text-center">Theme By Kahlil Bello <span id="year"></span> &copy; -----All Rights Reserved. </p>
              <p class="text-center small">
                <a style="color: white; text-decoration: none; cursor: pointer;" href="https://www.linkedin.com/in/kahlil-bello-4a6485123/">
                  This site is used for a senior project.</a>
              </p>
            </div>
          </div>
        </div>
      </footer>
      <div style="height:10px; background:#1F618D;"></div>
      <!-- FOOTER END -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- fontawesome script -->
    <script src="https://kit.fontawesome.com/013d75b213.js" crossorigin="anonymous"></script>
    <script>
    $('#year').text(new Date().getFullYear());
    </script>
  </body>
</html>
