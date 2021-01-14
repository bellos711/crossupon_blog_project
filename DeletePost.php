<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php Confirm_Login(); ?>
<?php
$SearchQueryParameter = $_GET['id'];
global $ConnectingDB;
//$SearchQueryParameter = $_GET["id"];
$sql = "SELECT * FROM posts WHERE id='$SearchQueryParameter'";
$stmtPost = $ConnectingDB->query($sql);
while ($DataRows=$stmtPost->fetch()) {
  $TitleToBeDeleted = $DataRows['title'];
  $CategoryToBeDeleted = $DataRows['category'];
  $ImageToBeDeleted = $DataRows['image'];
  $PostToBeDeleted = $DataRows['post'];
}//end while

//echo $ImageToBeDeleted;
if(isset($_POST["Submit"]))
{
    //query to delete post in db when it is good
    global $ConnectingDB;
    $sql = "DELETE FROM posts WHERE id='$SearchQueryParameter'";
    $Execute = $ConnectingDB->query($sql);

    if($Execute)
    {
      $Target_Path_To_DELETE_Image = "Uploads/$ImageToBeDeleted";
      unlink($Target_Path_To_DELETE_Image);
      $_SESSION["SuccessMessage"]="Post Deleted Successfully";
      Redirect_to("Posts.php");
    }//end if execute
    else
    {
      $_SESSION["ErrorMessage"]="Something went wrong. Try Again.";
      Redirect_to("Posts.php");
    }
}//ending of submit button if condition
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
    <title>Delete Post</title>
  </head>
  <body>
    <!-- NAVBAR -->
    <div style="height:10px; background:#1F618D;"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container" >
        <a href="#" class="navbar-brand"> CROSSUPON.COM</a>
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
            <h1><i class="fas fa-edit" style="color: orange;"></i> Delete Post</h1>
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
            <form class="" action="DeletePost.php?id=<?php echo $SearchQueryParameter; ?>" method="post" enctype="multipart/form-data">
              <div class="card bg-secondary text-light mb-3">
                <div class="card-body bg-dark">
                  <div class="form-group">
                    <label for="title"> <span class="FieldInfo"> Post Title: </span></label>
                    <input disabled class="form-control" type="text" name="PostTitle" id="title" placeholder="Type Title Here " value="<?php echo $TitleToBeDeleted; ?>">
                  </div>
                  <div class="form-group">
                    <span class="FieldInfo">Existing Category: </span>
                    <?php echo $CategoryToBeDeleted; ?>
                    <br>

                  </div>
                  <div class="form=group">
                    <span class="FieldInfo">Existing Image: </span>
                    <img class="mb-3" src="Uploads/<?php echo $ImageToBeDeleted; ?>" width="170px"; height="70px";>

                  </div>
                  <div class="form-group">
                    <label for="Post"> <span class="FieldInfo"> Post: </span></label>
                    <textarea disabled name="PostDescription" class="form-control" id="Post" rows="8" cols="80" >
                      <?php echo $PostToBeDeleted; ?>
                    </textarea>
                  </div>
                  <div class="row" >
                    <div class="col-lg-6 mb-2">
                      <a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-chevron-circle-left"></i> Back To Dashboard </a>
                    </div>
                    <div class="col-lg-6 mb-2">
                      <button type="submit" name="Submit" class="btn btn-danger btn-block">
                        <i class="fas fa-trash"></i> Delete
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
