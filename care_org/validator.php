<?php
session_start();
if(isset($_SESSION['signed_in'])){
  require_once 'defines/functions.php';
}
else{
  $_SESSION['error_msg'] = "Sign in to continue";
  header("Location: login_page_redirect");
  exit;
}
?>
