<?php
require_once "config.php";
if(empty($_SESSION['user_id']) AND empty($_SESSION['token'])){
    header("Location:index.php");
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <?php require_once 'header.php'; ?>
      <title>Hugo CMS Dashboard</title>
      <script type="text/javascript" src="js/jquery.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
  </head>
  <body>
    <?php require_once 'header1.php'; ?>

    <?php require_once 'sidebar.php'; ?>
  

  </body>
  <?php require_once 'script.php'; ?>
</html>
