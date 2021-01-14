<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
Confirm_Login(); ?>
<?php
if(isset($_POST["Submit"])){
  $PostTitle = $_POST["PostTitle"];
  $Category = $_POST["Category"];
  $Image = $_FILES["Image"]["name"]; //name grabs name of image need to specify where to save actual imageS
  $Target = "Uploads/".basename($_FILES["Image"]["name"]); //destination of image
  $PostText = $_POST["PostDescription"];
  //$Admin = "Kahlil"; //temp admin
  $Admin =  $_SESSION["UserName"];
  date_default_timezone_set("America/Los_Angeles");
  $CurrentTime=time();
  $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);

  if(empty($PostTitle)){
    $_SESSION["ErrorMessage"]="Title can't be empty.. fam";
    Redirect_to("AddNewPost.php");
  }
  elseif(strlen($PostTitle)<5){
    $_SESSION["ErrorMessage"]="Post title should be greater than 5 characters.";
    Redirect_to("AddNewPost.php");
  }
  elseif(strlen($PostText)>9999){
    $_SESSION["ErrorMessage"]="Post text should be less than 2000 characters.";
    Redirect_to("AddNewPost.php");
  }
  else
  {
    //query to insert post in db when it is good
    global $ConnectingDB;
    $sql="INSERT INTO posts(datetime,title,category,author,image,post)";
    $sql .="VALUES(:dateTime,:postTitle,:categoryName,:adminName,:imageName,:postDescription)";
    $stmt = $ConnectingDB->prepare($sql);//from DB.php
    $stmt->bindValue(':dateTime',$DateTime);
    $stmt->bindValue(':postTitle',$PostTitle);
    $stmt->bindValue(':categoryName',$Category);
    $stmt->bindValue(':adminName',$Admin);
    $stmt->bindValue(':imageName',$Image);
    $stmt->bindValue(':postDescription',$PostText);
    $Execute=$stmt->execute();
    move_uploaded_file($_FILES["Image"]["tmp_name"],$Target); //take this image with tmp name given by php

    if($Execute){
      $_SESSION["SuccessMessage"]="Post with id :".$ConnectingDB->lastInsertId()." Added Successfully";
      Redirect_to("AddNewPost.php");
    }//end if execute
    else{
      $_SESSION["ErrorMessage"]="Something went wrong. Try Again.";
      Redirect_to("AddNewPost.php");
    }// end else
  }//end else of set

}//End submit button if condition
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
    <title>Add New Post</title>
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
            <a href="MyProfile.php" class="nav-link"> <i class="fas fa-user-circle text-primary"></i> My Profile</a>
          </li>
          <li class="nav-item">
            <a href="Dashboard.php" class="nav-link">Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="Posts.php" class="nav-link">Posts</a>
          </li>
          <li class="nav-item">
            <a href="Categories.php" class="nav-link">Categories</a>
          </li>
          <li class="nav-item">
            <a href="Admins.php" class="nav-link">Manage Admins</a>
          </li>
          <li class="nav-item">
            <a href="Comments.php" class="nav-link">Comments</a>
          </li>
          <li class="nav-item">
            <a href="Blog.php?page=1" class="nav-link">Live Blog</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="Logout.php" class="nav-link text-primary">
            <i class="fas fa-times-circle"></i> Logout</a></li>
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
            <div class="col-md-12">
            <h1><i class="fas fa-edit" style="color: orange;"></i> Add New Post</h1>
            </div>
          </div>
        </div>
      </header>
      <!-- HEADER END -->

      <!-- Main Area -->
      <section class="container py-2 mb-4">
        <div class="row">
          <div class="offset-lg-1 col-lg-10" style="min-height: 400px;">
            <?php
              echo ErrorMessage();
              echo SuccessMessage();
            ?>
            <form class="" action="AddNewPost.php" method="post" enctype="multipart/form-data">
              <div class="card bg-secondary text-light mb-3">
                <div class="card-body bg-dark">
                  <div class="form-group">
                    <label for="title"> <span class="FieldInfo"> Post Title: </span></label>
                    <input class="form-control" type="text" name="PostTitle" id="title" placeholder="Type Title Here " value="">
                  </div>
                  <div class="form-group">
                    <label for="CategoryTitle"> <span class="FieldInfo"> Choose Category: </span></label>
                    <select class="form-control" id="CategoryTitle" name="Category">
                      <?php
                      //fetching all the categories from the category table
                      global $ConnectingDB;
                      $sql = "SELECT id,title FROM category";
                      $stmt = $ConnectingDB->query($sql);
                      while ($DateRows = $stmt->fetch()) {
                        $Id = $DateRows["id"];
                        $CategoryName = $DateRows["title"];
                       ?>
                       <option><?php  echo $CategoryName; ?></option>
                       <?php } ?>

                    </select>
                  </div>
                  <div class="form=group mb-3">
                    <div class="custom-file">
                      <input class="custom-file-input" type="File" name="Image" id="imageSelect" value="">
                      <label for="imageSelect" class="custom-file-label">  Select Image </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="Post"> <span class="FieldInfo"> Post: </span></label>
                    <textarea name="PostDescription" class="form-control" id="Post" rows="8" cols="80" ></textarea>
                  </div>
                  <div class="row" >
                    <div class="col-lg-6 mb-2">
                      <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-chevron-circle-left"></i> Back To Dashboard </a>
                    </div>
                    <div class="col-lg-6 mb-2">
                      <button type="submit" name="Submit" class="btn btn-success btn-block">
                        <i class="fas fa-check-circle"></i> Submit
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

      </section>



      <!-- End Main Area -->
      <!-- FOOTER -->
      <footer class="bg-dark text-white">
        <div class="container">
          <div class="row">
            <div class="col">
              <p class="lead text-center">Project By Kahlil <span id="year"></span> &copy; ----- </p>
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
