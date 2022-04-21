<?php 
  session_start();
?>

<?php

if (!isset($_SESSION["email"])) header("Location: index.php");

function main() {
  $fields = ["model", "office"];
  $required = ["model"];

  function isset_check(&$fields){
    foreach($fields as $field) {
        if(!isset($_GET[$field])) {
          $_GET[$field] = "";
        }
    }
  }

  if(isset_check($fields)) return 1;

  function empty_check(&$required){
    foreach($required as $field) {
        if(empty(trim($_GET[$field]))) {
            return 1;
        }
    }
  }

  if(empty_check($required)) return 2;

  $model = $_GET["model"];
  $office = $_GET["office"];
  echo $model;
  
}

$result = main();
echo $result;

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <title></title>
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <h1>Sample Text</h1>
      <p>This page will be build by PHP builder</p>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
