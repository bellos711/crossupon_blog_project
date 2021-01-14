<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $SearchQueryParameter = $_GET["id"]; ?>
<?php
if(isset($_POST["Submit"])){
  $Name = $_POST["CommenterName"];
  $Email = $_POST["CommenterEmail"];
  $Comment = $_POST["CommenterThoughts"];
  //$Admin = "Kahlil"; //temp admin
  date_default_timezone_set("America/Los_Angeles");
  $CurrentTime=time();
  $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);

  if(empty($Name) || empty($Email) || empty($Comment)){
    $_SESSION["ErrorMessage"]="All fields must be filled out";
    Redirect_to("FullPost.php?id=$SearchQueryParameter");
  }
  elseif(strlen($Comment)>1999){
    $_SESSION["ErrorMessage"]="Comment length should be less than 2000";
    Redirect_to("FullPost.php?id=$SearchQueryParameter");
  }
  else
  {
    //query to insert comment in db when it is good
    global $ConnectingDB;
    $sql="INSERT INTO comments(datetime,name,email,comment,approvedby,status,post_id)";
    $sql .="VALUES(:dateTime,:name,:email,:comment,'Pending','OFF',:postIdFromURL)";
    $stmt = $ConnectingDB->prepare($sql);//from DB.php
    $stmt->bindValue(':dateTime', $DateTime);
    $stmt->bindValue(':name',$Name);
    $stmt->bindValue(':email', $Email);
    $stmt->bindValue(':comment', $Comment);
    $stmt->bindValue(':postIdFromURL',$SearchQueryParameter);
    $Execute=$stmt->execute();

    if($Execute){
      $_SESSION["SuccessMessage"]="Comment Submitted Successfully";
      Redirect_to("FullPost.php?id=$SearchQueryParameter");
    }//end if execute
    else{
      $_SESSION["ErrorMessage"]="Something went wrong. Try Again.";
      Redirect_to("FullPost.php?id=$SearchQueryParameter");
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
    <title>Full Post Page</title>
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
            <a href="#" class="nav-link">Contact Us</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">Features</a>
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
      <div class="container">
        <div class="row mt-4">

          <!-- MAIN AREA START -->
          <div class="col-sm-8">
            <!-- <h1>Complete CROSSUPON Blog with CMS</h1>
            <h1 class="lead">Complete Blog Using php</h1> -->
            <?php
              echo ErrorMessage();
              echo SuccessMessage();
            ?>
            <?php
            global $ConnectingDB;
            if(isset($_GET["SearchButton"]))
            {
              $Search = $_GET["Search"];
              $sql = "SELECT * FROM posts
              WHERE datetime LIKE :search
              OR title LIKE :search
              OR category LIKE :search
              OR post LIKE :search";
              $stmt = $ConnectingDB->prepare($sql);
              $stmt->bindValue(':search','%'.$Search.'%'); //& search for sql seach using like
              $stmt->execute();
            }//end if isset SearchButton
            else
            {
              $PostIdFromURL = $_GET["id"];
              if (!isset($PostIdFromURL)) {
                $_SESSION["ErrorMessage"]="Bad Request!";
                Redirect_to("Blog.php?page=1");
              }//end if !isset
              $sql = "SELECT * FROM posts WHERE id='$PostIdFromURL'";
              $stmt = $ConnectingDB->query($sql);
              $Result = $stmt->rowcount();
              if ($Result!=1) {
                $_SESSION["ErrorMessage"]="Bad Request!";
                Redirect_to("Blog.php?page=1");
              }//endif
            }//end else
            while ($DataRows = $stmt->fetch())
            {
              $PostId = $DataRows["id"];
              $DateTime = $DataRows["datetime"];
              $PostTitle = $DataRows["title"];
              $Category = $DataRows["category"];
              $Admin = $DataRows["author"];
              $Image = $DataRows["image"];
              $PostDescription = $DataRows["post"];

             ?>
             <div class="card">
               <img src="Uploads/<?php echo htmlentities($Image); ?>" style="max-height:500px;" class="img-fluid card-img-top" />
               <div class="card-body">
                 <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
                 <small class="text-muted">Category: <span class="text-dark"><a href="Blog.php?category=<?php echo htmlentities($Category); ?>"><?php echo htmlentities($Category); ?></a></span>
                 <br>Written by <span class="text-dark"><a href="Profile.php?username=<?php echo htmlentities($Admin);?>"><?php echo htmlentities($Admin);  ?></a></span> On <?php echo htmlentities($DateTime); ?></small>
                 <!-- <span style="float:right;" class="badge badge-dark text-light">Comments</span> -->
                 <hr>
                 <p class="card-text">
                    <?php  echo nl2br($PostDescription); ?></p>
                    <!-- nl2br lets you apply html and styles in your p tag -->
               </div>
             </div>
             <?php }//endwhile ?>
             <!-- COMMENT SECTION START -->
             <!-- FETCHING EXISTING COMMENTS START -->
             <span class="FieldInfo">Comments</span>
             <br><br>
             <?php
             global $ConnectingDB;
             $sql = "SELECT * FROM comments WHERE post_id='$SearchQueryParameter' AND status='ON'";
             $stmt = $ConnectingDB->query($sql);
             while ($DataRows = $stmt->fetch())
             {
               $CommentDate = $DataRows['datetime'];
               $CommenterName = $DataRows['name'];
               $CommentContent = $DataRows['comment'];

              ?>
              <div>

                <div class="media CommentBlock">
                  <img class="d-block img-fluid align-self-center" src="images/comment.png" alt="">
                  <div class="media-body ml-2">
                    <h6 class="lead font-weight-bold"><?php echo $CommenterName; ?></h6>
                    <p class="small"><?php echo $CommentDate; ?></p>
                    <p><?php echo $CommentContent; ?></p>
                  </div>
                </div>
              </div>
              <hr>
              <?php }//endwhile ?>
             <!-- FETCHING EXISTING COMMENTS END -->

             <div class="">
               <form class="" action="FullPost.php?id=<?php echo $SearchQueryParameter; ?>" method="post">
                 <div class="card mb-3">
                   <div class="card-header">
                     <h5 class="FieldInfo">Share thoughts about this post.</h5>
                   </div>
                     <div class="card-body">
                       <div class="form-group">
                         <div class="input-group">
                           <div class="input-group-prepend">
                             <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                           </div>
                           <input class="form-control" type="text" name="CommenterName" placeholder="Name" value="">
                         </div>
                       </div>
                       <div class="form-group">
                         <div class="input-group">
                           <div class="input-group-prepend">
                             <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                           </div>
                           <input class="form-control" type="email" name="CommenterEmail" placeholder="Email" value="">
                         </div>
                       </div>
                       <div class="form-group">
                         <textarea name="CommenterThoughts" class ="form-control" rows="5" cols="80"></textarea>
                       </div>
                       <div class="">
                         <button class="btn btn-primary" type="submit" name="Submit">Submit</button>
                       </div>

                     </div>
                 </div>

               </form>
             </div>
             <!-- COMMENT SECTION END -->
          </div>
          <!-- MAIN AREA END -->

          <!-- SIDE AREA START -->
          <div class=" col-sm-4">
            <div class="card mt-4">
              <div class="card-body">
                <img src="images/samplead.jpg" class="d-block img-fluid mb-3 mx-auto" alt="">
                <div class="text-center ">
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </div>
              </div>
            </div>
            <br>
            <div class="card">
              <div class="card-header bg-dark text-light">
                <h2 class="lead">Sign Up</h2>
              </div>
              <div class="card-body">
                <button type="button" onclick="window.location.href = 'RegisterPublic.php';" class="btn btn-info btn-block text-center text-white mb-3" name="button">Join the Forum</button>
                <button type="button" onclick="window.location.href = 'Login.php';" class="btn btn-warning btn-block text-center text-dark mb-3" name="button">Login</button>

                <!-- <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder = "Enter your email" name="" value="">
                  <div class="input-group-append">
                    <button type="button" class= "btn btn-primary btn-sm text-center text-white" name="button">Subscribe Now</button>
                  </div>
                </div> -->
              </div>
            </div>
            <br>
            <div class="card">
              <div class="card-header bg-dark text-light">
                <h2 class="lead">Categories</h2>
                </div>
                <div class="card-body">
                  <?php
                  global $ConnectingDB;
                  $sql = "SELECT * FROM category ORDER BY id desc";
                  $stmt = $ConnectingDB->query($sql);
                  while ($DataRows = $stmt->fetch()) {
                    $CategoryId = $DataRows["id"];
                    $CategoryName = $DataRows["title"];
                   ?>
                    <a href="Blog.php?category=<?php echo $CategoryName; ?>"> <span class="heading"> <?php echo $CategoryName ?> </span> </a>
                   <br>
                   <?php }//end while ?>
              </div>
            </div>
            <br>
            <div class="card">
              <div class="card-header bg-dark text-white">
                <h2 class="lead">Most Recent Posts</h2>
              </div>
              <div class="card-body">
                <?php
                global $ConnectingDB;
                $sql="SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
                $stmt = $ConnectingDB->query($sql);
                while ($DataRows=$stmt->fetch()) {
                  $Id = $DataRows['id'];
                  $Title = $DataRows['title'];
                  $DateTime = $DataRows['datetime'];
                  $Image = $DataRows['image'];
                ?>
                <div class="media">
                  <img src="Uploads/<?php echo htmlentities($Image); ?>" alt="" class="d-block img-fluid align-self-start" width="90" height="97">
                  <div class="media-body ml-2">
                    <a href="FullPost.php?id=<?php echo htmlentities($Id); ?>" target="_blank"><h6 class="lead"><?php echo htmlentities($Title); ?></h6></a>
                    <p class="small"><?php echo htmlentities($DateTime); ?></p>
                  </div>
                </div>
                <hr>
              <?php }//endwhile  ?>
              </div>
            </div>

          </div>
          <!-- SIDE ARE END -->



        </div>
      </div>
      <!-- HEADER END -->
      <br>
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
