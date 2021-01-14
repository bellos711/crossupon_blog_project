<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
//echo $_SESSION["TrackingURL"];
Confirm_Login();
$ThePermission = $_SESSION["PermissionNo"];
?>
<?php

if(isset($_POST["Submit"])){
  $Category = $_POST["CategoryTitle"];
  //$Admin = "Kahlil"; //temp admin
  $Admin =  $_SESSION["UserName"];
  date_default_timezone_set("America/Los_Angeles");
  $CurrentTime=time();
  $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);

  if(empty($Category)){
    $_SESSION["ErrorMessage"]="All fields must be filled out";
    Redirect_to("Categories.php");
  }
  elseif(strlen($Category)<3){
    $_SESSION["ErrorMessage"]="Category title should be greater than 2 characters";
    Redirect_to("Categories.php");
  }
  elseif(strlen($Category)>49){
    $_SESSION["ErrorMessage"]="Characters should be less than 50";
    Redirect_to("Categories.php");
  }
  else
  {
    //query to insert category in db when it is good
    global $ConnectingDB;
    $sql="INSERT INTO category(title,author,datetime)";
    $sql .="VALUES(:categoryName,:adminName,:dateTime)";
    $stmt = $ConnectingDB->prepare($sql);//from DB.php
    $stmt->bindValue(':categoryName',$Category);
    $stmt->bindValue(':adminName', $Admin);
    $stmt->bindValue(':dateTime', $DateTime);
    $Execute=$stmt->execute();

    if($Execute){
      $_SESSION["SuccessMessage"]="Category with id :".$ConnectingDB->lastInsertId()." Added Successfully";
      Redirect_to("Categories.php");
    }//end if execute
    else{
      $_SESSION["ErrorMessage"]="Something went wrong. Try Again.";
      Redirect_to("Categories.php");
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
    <title>Categories</title>
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
            <h1><i class="fas fa-edit" style="color: orange;"></i> Manage Categories</h1>
            </div>
          </div>
        </div>
      </header>
      <!-- HEADER END -->

      <?php
      if ($ThePermission==2 || $ThePermission==1) {
      ?>
      <!-- Main Area -->
      <section class="container py-2 mb-4">
        <div class="row">
          <div class="offset-lg-1 col-lg-10" style="min-height: 400px;">
            <?php
              echo ErrorMessage();
              echo SuccessMessage();
            ?>
            <form class="" action="Categories.php" method="post">
              <div class="card bg-secondary text-light mb-3">
                <div class="card-header">
                  <h1>Add New Category</h1>
                </div>
                <div class="card-body bg-dark">
                  <div class="form-group">
                    <label for="title"> <span class="FieldInfo"> Category Title: </span></label>
                    <input class="form-control" type="text" name="CategoryTitle" id="title" placeholder="Type Title Here " value="">
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

            <!-- DELETE EXISTING CATEGORIES SECTION -->
            <h2>Existing Categories</h2>
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th>No. </th>
                  <th>Date/Time</th>
                  <th>Category Name</th>
                  <th>Creator Name</th>
                  <th>Action</th>
                </tr>
              </thead>

            <?php
            global $ConnectingDB;
            $sql="SELECT* FROM category ORDER BY id desc";
            $Execute = $ConnectingDB->query($sql);
            $SrNo = 0;
            while($DataRows=$Execute->fetch()){
              $CategoryId = $DataRows["id"];
              $CategoryDate = $DataRows["datetime"];
              $CategoryName = $DataRows["title"];
              $CreatorName = $DataRows["author"];
              $SrNo++;
              //if (strlen($CommenterName)>10) {$CommenterName = substr($CommenterName, 0, 10).'..';}
              //if (strlen($DateTimeOfComment)>11) {$DateTimeOfComment = substr($DateTimeOfComment, 0, 11).'..';}
             ?>
             <tbody>
               <tr>
                 <td><?php echo htmlentities($SrNo); ?></td>
                 <td><?php echo htmlentities($CategoryDate); ?></td>
                 <td><?php echo htmlentities($CategoryName); ?></td>
                 <td><?php echo htmlentities($CreatorName); ?></td>
                 <td> <a href="DeleteCategory.php?id=<?php echo $CategoryId ?>" class="btn btn-danger">Delete</a></td>
               </tr>
             </tbody>
           <?php }//endwhile ?>
           </table>
          </div>
        </div>

      </section>
    <?php }//endif
    else {?>
      <div class="container">
      <?php
      $_SESSION["ErrorMessage"]="This Page reqruies elevation";
      echo ErrorMessage();
      echo SuccessMessage();
      }?>
      </div>


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
