<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
Confirm_Login();
$ThePermission = $_SESSION["PermissionNo"];
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
    <title>Comments</title>
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
            <h1><i class="fas fa-comments" style="color: orange;"></i> Manage Comments</h1>
            </div>
          </div>
        </div>
      </header>
      <!-- HEADER END -->

      <?php
      if ($ThePermission==2 || $ThePermission==1) {
      ?>
      <!-- main area start -->
      <section class="container py-2 mb-4" >
        <div class="row" style="min-height:30px;">
          <div class="col-lg-22" style="min-height:400px;">
            <?php
              echo ErrorMessage();
              echo SuccessMessage();
            ?>
            <h2>Unapproved Comments</h2>
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th>No. </th>
                  <th>Date/Time</th>
                  <th>Name</th>
                  <th>Comment</th>
                  <th>Approve</th>
                  <th>Delete</th>
                  <th>Details</th>
                </tr>
              </thead>


            <?php
            global $ConnectingDB;
            $sql="SELECT* FROM comments WHERE status='OFF' ORDER BY id desc";
            $Execute = $ConnectingDB->query($sql);
            $SrNo = 0;
            while($DataRows=$Execute->fetch()){
              $CommentId = $DataRows["id"];
              $DateTimeOfComment = $DataRows["datetime"];
              $CommenterName = $DataRows["name"];
              $CommentContent = $DataRows["comment"];
              $CommentPostId = $DataRows["post_id"];
              $SrNo++;
              if (strlen($CommenterName)>10) {$CommenterName = substr($CommenterName, 0, 10).'..';}
              //if (strlen($DateTimeOfComment)>11) {$DateTimeOfComment = substr($DateTimeOfComment, 0, 11).'..';}
             ?>
             <tbody>
               <tr>
                 <td><?php echo htmlentities($SrNo); ?></td>
                 <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                 <td><?php echo htmlentities($CommenterName); ?></td>
                 <td><?php echo htmlentities($CommentContent); ?></td>
                 <td> <a href="ApproveComments.php?id=<?php echo $CommentId ?>" class="btn btn-warning">Approve</a></td>
                 <td> <a href="DeleteComments.php?id=<?php echo $CommentId ?>" class="btn btn-danger">Delete</a></td>
                 <td style="min-width:140px;"><a class ="btn btn-primary" href = "FullPost.php?id=<?php echo $CommentPostId;?>" target="_blank">Live Preview</a></td>
               </tr>
             </tbody>
           <?php }//endwhile ?>
           </table>

           <!-- UNAPROVE SECRION-->
           <h2>Approved Comments</h2>
           <table class="table table-striped table-hover">
             <thead class="thead-dark">
               <tr>
                 <th>No. </th>
                 <th>Date/Time</th>
                 <th>Name</th>
                 <th>Comment</th>
                 <th>Unapprove</th>
                 <th>Delete</th>
                 <th>Details</th>
               </tr>
             </thead>

           <?php
           global $ConnectingDB;
           $sql="SELECT* FROM comments WHERE status='ON' ORDER BY id desc";
           $Execute = $ConnectingDB->query($sql);
           $SrNo = 0;
           while($DataRows=$Execute->fetch()){
             $CommentId = $DataRows["id"];
             $DateTimeOfComment = $DataRows["datetime"];
             $CommenterName = $DataRows["name"];
             $CommentContent = $DataRows["comment"];
             $CommentPostId = $DataRows["post_id"];
             $SrNo++;
             if (strlen($CommenterName)>10) {$CommenterName = substr($CommenterName, 0, 10).'..';}
             //if (strlen($DateTimeOfComment)>11) {$DateTimeOfComment = substr($DateTimeOfComment, 0, 11).'..';}
            ?>
            <tbody>
              <tr>
                <td><?php echo htmlentities($SrNo); ?></td>
                <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                <td><?php echo htmlentities($CommenterName); ?></td>
                <td><?php echo htmlentities($CommentContent); ?></td>
                <td style="min-width:140px;"> <a href="UnapproveComments.php?id=<?php echo $CommentId ?>" class="btn btn-warning">Unapprove</a></td>
                <td> <a href="DeleteComments.php?id=<?php echo $CommentId ?>" class="btn btn-danger">Delete</a></td>
                <td style="min-width:140px;"><a class ="btn btn-primary" href = "FullPost.php?id=<?php echo $CommentPostId;?>" target="_blank">Live Preview</a></td>
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
      <!-- main area end -->
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
