<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
if (isset($_GET["id"])) {
  $SearchQueryParameter = $_GET["id"];
  global $ConnectingDB;
  //$Admin = $_SESSION["AdminName"];
  $sql = "DELETE FROM category WHERE id='$SearchQueryParameter'";
  $Execute = $ConnectingDB->query($sql);
  if ($Execute) {
      $_SESSION["SuccessMessage"]="Comment Removed Successfully";
      Redirect_to("Comments.php");
  }else{
    $_SESSION["ErrorMessage"]="Soemthing Went Wrong. Try Again";
    Redirect_to("Comments.php");
  }//end else
}


 ?>
