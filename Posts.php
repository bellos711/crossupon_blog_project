<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
//echo $_SESSION["TrackingURL"];
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
    <title>Posts</title>
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
            <h1><i class="fas fa-pen-alt" style="color: orange;"></i> Blog Posts</h1>
            </div>
            <div class="col-lg-3 mb-2">
              <a href="AddNewPost.php" class="btn btn-warning btn-block">
                <i class="fas fa-edit"></i> Add New Post
              </a>
            </div>
            <div class="col-lg-3 mb-2">
              <a href="Categories.php" class="btn btn-warning btn-block">
                <i class="fas fa-folder-plus"></i> Add New Category
              </a>
            </div>
            <div class="col-lg-3 mb-2">
              <a href="Admins.php" class="btn btn-warning btn-block">
                <i class="fas fa-user-plus"></i> Add New Admin
              </a>
            </div>
            <div class="col-lg-3 mb-2">
              <a href="Comments.php" class="btn btn-warning btn-block">
                <i class="fas fa-check"></i> Approve Comments
              </a>
            </div>
          </div>
        </div>
      </header>
      <!-- HEADER END -->

      <?php
      if ($ThePermission==2 || $ThePermission==1) {
      ?>
      <!-- MAIN AREA -->
      <section class="container py-2 mb-4">
        <div class="row">
          <div class="col-lg-12">
            <?php
              echo ErrorMessage();
              echo SuccessMessage();
            ?>
            <table class="table table=striped table-hover">
              <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Date&Time</th>
                <th>Author</th>
                <th>Banner</th>
                <th>Comments</th>
                <th>Action</th>
                <th>Live Preview</th>
              </tr>
              </thead>
              <?php
              global $ConnectingDB;
              $sql = "SELECT * FROM posts";
              $stmt = $ConnectingDB->query($sql);
              $Sr = 0;
              while ($DataRows = $stmt->fetch())
              {
                $Id = $DataRows["id"];
                $DateTime = $DataRows["datetime"];
                $PostTitle = $DataRows["title"];
                $Category = $DataRows["category"];
                $Admin = $DataRows["author"];
                $Image = $DataRows["image"];
                $PostText = $DataRows["post"];
                $Sr++;
               ?>
               <tbody>
               <tr>
                 <td><?php echo $Sr ?></td>
                 <td class="table-primary">
                   <?php if (strlen($PostTitle)>20){$PostTitle = substr($PostTitle,0,15).'..';} echo $PostTitle//if length of posttitle greater than 20, only show 0-15th char  ?>
                 </td>
                 <td><?php if (strlen($Category)>8){$Category = substr($Category,0,8).'..';} echo $Category ?></td>
                 <td><?php if (strlen($DateTime)>20){$DateTime = substr($DateTime,0,20).'..';}echo $DateTime ?></td>
                 <td><?php if (strlen($Admin)>7){$Admin = substr($Admin,0,7).'..';} echo $Admin ?></td>
                 <td> <img src="Uploads/<?php echo $Image ?>" width="120px"; height="50px;"></td>
                 <td>
                     <?php
                     $Total = ApproveCommentsAccordingToPost($Id);
                     if ($Total > 0) {
                       ?>
                       <span class="badge badge-success">
                         <?php
                       echo $Total; ?>
                     </span>
                   <?php } //end if because we needed to make span not show up if comments 0 ?>


                   <?php
                   $Total = UnapproveCommentsAccordingtoPost($Id);
                   if ($Total > 0) {
                     ?>
                     <span class="badge badge-danger">
                       <?php
                     echo $Total; ?>
                   </span>
                 <?php } //end if because we needed to make span not show up if comments 0 ?>
                 </td>
                 <td>
                   <a href="EditPost.php?id=<?php echo $Id; ?>"><span class="btn btn-warning">Edit</span></a>
                   <a href="DeletePost.php?id=<?php echo $Id; ?>"><span class="btn btn-danger">Delete</span></a>
                 </td>
                 <td><a href="FullPost.php?id=<?php echo $Id; ?>" target="_blank"><span class="btn btn-primary">Live Preview</span></a></td>
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
      <!-- END MAIN AREA -->

      <!-- FOOTER -->
      <footer class="bg-dark text-white">
        <div class="container">
          <div class="row">
            <div class="col">
              <p class="lead text-center">Project By Kahlil Bello <span id="year"></span> &copy; ----- </p>
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
