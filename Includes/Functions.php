<?php require_once("Includes/DB.php"); ?>
<?php
function Redirect_to($New_Location)
{
  header("Location:".$New_Location);
  exit;
}//end Redirect_to









function CheckUserNameExistsOrNot($UserName){
  global $ConnectingDB;
  $sql = "SELECT username FROM admins WHERE username=:userName";
  $stmt = $ConnectingDB->prepare($sql);
  $stmt->bindValue(':userName',$UserName);
  $stmt->execute();
  $Result = $stmt->rowcount();
  if ($Result==1) {return true;}
  else{return false;}
}//end CheckUserNameExistsOrNot

function Login_Attempt($UserName, $Password){
  global $ConnectingDB;
  $sql = "SELECT * FROM admins WHERE username=:userName AND password=:passWord LIMIT 1";
  $stmt = $ConnectingDB->prepare($sql);
  $stmt->bindValue(':userName',$UserName);
  $stmt->bindValue(':passWord',$Password);
  $stmt->execute();
  $Result = $stmt->rowcount();
  if ($Result==1) {
    return $Found_Account=$stmt->fetch();
  }else {
    return null;
  }//end result==good checking  if user in db
}//end login_Attempt


function Confirm_Login(){
  if (isset($_SESSION["UserId"])){
    return true;
  }//end if isset
  else{
    $_SESSION["ErrorMessage"]="Login Required!";
    Redirect_to("Login.php");
  }
}

function TotalPosts(){
  global $ConnectingDB;
  $sql = "SELECT COUNT(*) FROM posts";
  $stmt = $ConnectingDB->query($sql);
  $TotalRows = $stmt->fetch();
  $TotalPosts = array_shift($TotalRows);
  echo $TotalPosts;
}//end totalposts

function TotalCategories(){
  global $ConnectingDB;
  $sql = "SELECT COUNT(*) FROM category";
  $stmt = $ConnectingDB->query($sql);
  $TotalRows = $stmt->fetch();
  $TotalCategories = array_shift($TotalRows);
  echo $TotalCategories;
}//end totalcategories

function TotalAdmins(){
  global $ConnectingDB;
  $sql = "SELECT COUNT(*) FROM admins";
  $stmt = $ConnectingDB->query($sql);
  $TotalRows = $stmt->fetch();
  $TotalAdmins = array_shift($TotalRows);
  echo $TotalAdmins;
}//end totaladmins

function TotalComments(){
  global $ConnectingDB;
  $sql = "SELECT COUNT(*) FROM comments";
  $stmt = $ConnectingDB->query($sql);
  $TotalRows = $stmt->fetch();
  $TotalComments = array_shift($TotalRows);
  echo $TotalComments;
}//end totalComments;


function ApproveCommentsAccordingToPost($PostId){
  global $ConnectingDB;
  $sqlApprove = "SELECT COUNT(*) FROM comments WHERE post_id = '$PostId' AND status='ON'";
  $stmtApprove = $ConnectingDB->query($sqlApprove);
  $RowsTotal = $stmtApprove->fetch();
  $Total = array_shift($RowsTotal); //to convert it into a string use array_shift
  return $Total;
}


function UnapproveCommentsAccordingtoPost($PostId){
  global $ConnectingDB;
  $sqlUnapprove = "SELECT COUNT(*) FROM comments WHERE post_id = '$PostId' AND status='OFF'";
  $stmtUnapprove = $ConnectingDB->query($sqlUnapprove);
  $RowsTotal = $stmtUnapprove->fetch();
  $Total = array_shift($RowsTotal); //to convert it into a string use array_shift
  return $Total;
}

 ?>
