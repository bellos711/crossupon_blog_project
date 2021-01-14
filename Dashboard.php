<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
//echo $_SESSION["TrackingURL"];
Confirm_Login(); ?>
<?php
// FETCHING EXISTING ADMIN/S INFO
$AdminId = $_SESSION["UserId"];
$ThePermission = $_SESSION["PermissionNo"];
global $ConnectingDB;
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
    <title>Dashboard</title>
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
            <h1><i class="fas fa-cog" style="color: orange;"></i> Dashboard</h1>
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

      <!-- MAIN AREA -->
      <section class="container py-2 mb-4">
        <?php
          echo ErrorMessage();
          echo SuccessMessage();
        ?>
        <div class="row">

          <!-- LEFT SIDE AREA OF DASHBOARD START -->
          <div class="col-lg-2 d-none d-md-block">
            <div class="card text-center bg-dark text-white mb-3">
              <div class="card-body">
                <h1 class="lead">Posts</h1>
                <h4 class="display-5">
                  <i class="fas fa-pencil-alt"></i>
                  <?php
                  TotalPosts();
                   ?>
                </h4>
              </div>
            </div>

            <div class="card text-center bg-dark text-white mb-3">
              <div class="card-body">
                <h1 class="lead">Categories</h1>
                <h4 class="display-5">
                  <i class="fas fa-folder"></i>
                  <?php
                  TotalCategories();
                   ?>
                </h4>
              </div>
            </div>

            <div class="card text-center bg-dark text-white mb-3">
              <div class="card-body">
                <h1 class="lead">Users</h1>
                <h4 class="display-5">
                  <i class="fas fa-users"></i>
                  <?php
                  TotalAdmins();
                   ?>
                </h4>
              </div>
            </div>

            <div class="card text-center bg-dark text-white mb-3">
              <div class="card-body">
                <h1 class="lead">Comments</h1>
                <h4 class="display-5">
                  <i class="fas fa-comments"></i>
                  <?php
                    TotalComments();
                   ?>
                </h4>
              </div>
            </div>

          </div>
          <!-- LEFT SIDE AREA OF DASHBOARD END -->

          <!-- RIGHT SIDE AREA START -->
          <div class="col-lg-10">
            <h1>Recent Posts</h1>
            <table class="table table-striped table-hover">
              <thead class="thead-dark">
                <tr>
                  <th>No.</th>
                  <th>Title</th>
                  <th>Date&Time</th>
                  <th>Author</th>
                  <th>Comments</th>
                  <th>Details</th>
                </tr>
              </thead>
              <?php
              $SrNo = 0;
              global $ConnectingDB;
              $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
              $stmt = $ConnectingDB->query($sql);
              while($DataRows=$stmt->fetch()){
                $PostId = $DataRows["id"];
                $DateTime = $DataRows["datetime"];
                $Author = $DataRows["author"];
                $Title = $DataRows["title"];
                $SrNo++;

               ?>
               <tbody>
                 <tr>
                   <td><?php echo $SrNo; ?></td>
                   <td><?php echo $Title; ?></td>
                   <td><?php echo $DateTime; ?></td>
                   <td><?php echo $Author; ?></td>
                   <td>
                       <?php
                       $Total = ApproveCommentsAccordingToPost($PostId);
                       if ($Total > 0) {
                         ?>
                         <span class="badge badge-success">
                           <?php
                         echo $Total; ?>
                       </span>
                     <?php } //end if because we needed to make span not show up if comments 0 ?>


                     <?php
                     $Total = UnapproveCommentsAccordingtoPost($PostId);
                     if ($Total > 0) {
                       ?>
                       <span class="badge badge-danger">
                         <?php
                       echo $Total; ?>
                     </span>
                   <?php } //end if because we needed to make span not show up if comments 0 ?>
                   </td>
                   <td> <a target="_blank" href="FullPost.php?id=<?php echo $PostId; ?>" >
                     <span class="btn btn-info">Preview</span> </a>
                   </td>
                 </tr>
               </tbody>
             <?php }//end of while loop ?>
            </table>
          </div>

          <!-- RIGHT SIDE AREA END -->
        </div>
      </section>
      <!-- END MAIN AREA -->

      <!-- FOOTER -->
      <footer class="bg-dark text-white">
        <div class="container">
          <div class="row">
            <div class="col">
              <p class="lead text-center">Project By Kahlil Bello <span id="year"></span> &copy; ----- </p>
              <p class="text-center small">
                <a style="color: white; text-decoration: none; cursor: pointer;" href="https://www.linkedin.com/in/kahlil-bello-4a6485123/">
                  This site is used for a senior project. <?php echo "$ThePermission"; ?></a>
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
