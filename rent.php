<?php 
  session_start();
?>

<?php

  if (!isset($_SESSION["email"])) {
    $_SESSION["redirect"] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    die();
  }

  function main() {

    require_once "db_connect.php";
    $link = mysqli_connect($server, $user, $pass, $db);
    if (!$link) {
      die("Connection failed: ".mysqli_connect_error());
    }

    $fields = ["model", "office"];
    $required = ["model"];

    function redirect() {
      header("Location: 404.php");
      die();
    }

    function isset_check(&$fields){
      foreach($fields as $field) {
          if(!isset($_GET[$field])) {
            $_GET[$field] = "";
          }
      }
    }

    isset_check($fields);

    function empty_check(&$required){
      foreach($required as $field) {
          if(empty(trim($_GET[$field]))) {
              return 1;
          }
      }
    }

    if(empty_check($required)) redirect();

    $model = $_GET["model"];
    $office_ID = $_GET["office"];
    $data = [];
    
    function office_check($link, $office_ID) {
      $query = "SELECT name, address, post FROM zxc_offices WHERE ID=?";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "i", $office_ID);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
  
      if(mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $office_name, $address, $post);
        mysqli_stmt_fetch($stmt);
        $data = array("office_name" => $office_name, "address" => $address, "post" => $post);
        return $data;
      }
      else {
        return 1;
      }
      mysqli_stmt_close($stmt);
    }
    if(strval($office_ID) == "0") redirect(); // just because enos server thinks that if $office_ID is empty, then it equals 0 -_-
    if(!empty($office_ID)) {
      $office_data = office_check($link, $office_ID);
      if ($office_data == 1) redirect();
      $data = array_merge($data, $office_data);
    }

    function offices_get($link) {
      $query = "SELECT name FROM zxc_offices";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $office_name);
      $data = [];
      array_push($data, "dummy");
      while(mysqli_stmt_fetch($stmt)) {
        array_push($data, $office_name);
      }
      return $data;
    }

    $offices_data = offices_get($link);
    $data["offices"] = $offices_data;

    function model_check($link, $model) {
      $query = "SELECT name, description, price_hr, image FROM zxc_model WHERE ID=?";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "i", $model);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);

      if(mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $model_name, $description, $price, $img);
        mysqli_stmt_fetch($stmt);
      $data = array("model_name" => $model_name, "description" => $description, "price" => $price, "img" => $img);
        return $data;
      }
      else {
        return 1;
      }
    }

    $model_data = model_check($link, $model);
    if ($model_data == 1) redirect();
    $data = array_merge($data, $model_data);

    function model_count_check($link, $model) {
      $query = "SELECT COUNT(model_id) FROM zxc_vehicle WHERE model_id=? AND status='available'";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "i", $model);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $model_count);
      mysqli_stmt_fetch($stmt);

      if($model_count > 0) {
        $data = array("model_count" => $model_count);
        return $data;
      }
      else {
        return 1;
      }
    }

    $model_count_data = model_count_check($link, $model);
    if ($model_count_data != 1) $data = array_merge($data, $model_count_data); 

    function user_data_check($link) {
      $query = "SELECT first_name, last_name, phone FROM zxc_account WHERE email=?";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "s", $_SESSION["email"]);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);

      if(mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $first_name, $last_name, $phone);
        mysqli_stmt_fetch($stmt);
        $data = array("first_name" => $first_name, "last_name" => $last_name, "phone" => $phone);
        return $data;
      }
      mysqli_stmt_close($stmt);
    }

    $user_data = user_data_check($link);
    $data = array_merge($data, $user_data);

    mysqli_close($link);
    return $data;
  }

  $data = main();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/rent.css">
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        let minuses = document.getElementsByClassName("minus");
        let pluses = document.getElementsByClassName("plus");

        function decrease(e) {
          let quantity_field = e.target.parentNode.children[1];
          quantity = parseInt(quantity_field.value);
          if(!quantity) quantity_field.value = 1;

          if(quantity-1 > 0) {
              quantity--;
              quantity_field.value = quantity;
          }
        }
        function increase(e) {
          let quantity_field = e.target.parentNode.children[1];
          quantity = parseInt(quantity_field.value);
          if(!quantity) quantity_field.value = 1;

          if(e.target.parentNode.className == "quantity_button") {
            if(quantity+1 <= <?php echo $data["model_count"];?>) {
              quantity++;
              quantity_field.value = quantity;
            }
          }
          else if (e.target.parentNode.className == "time_button") {
            if(quantity+1 <= 12) {
              quantity++;
              quantity_field.value = quantity;
            }
          }
        }
        for (let minus of minuses) {
          minus.addEventListener("click", decrease);
        }
        for (let plus of pluses) {
          plus.addEventListener("click", increase);
        }
      });
    </script>
    <title><?php echo $data["model_name"]?></title>
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <div class="content">
        <div class="model">
            <h1>
              <?php echo $data["model_name"];?>
            </h1>
          <div class="model_image">
            <img src="data:image/png;charset=utf8;base64,<?php echo base64_encode($data["img"]);?>">
          </div>
          <div class="info">
            <div class="desc">
              <p><?php echo $data["description"];?></p>
            </div>
            <div class="add_info">
              <div>
                <p>Price: <?php echo $data["price"];?>/hr</p>
              </div>
              <div>
                <p>In stock: <?php echo $data["model_count"]?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="order_form">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <label for="nameFirst" id="top_label">First name</label>
            <input type="text" id="NameFirst" name="nameFirst"  maxlength="20" value="<?php echo $data["first_name"];  ?>">
            <label for="NameLast">Last name</label>
            <input type="text" id="NameLast" name="nameLast"  maxlength="20" value="<?php echo $data["last_name"];  ?>">
            <label for="phone">Phone</label>
            <input type="tel" pattern="[+0-9]*" id="phone" name="phone" maxlength="15" value="<?php echo $data["phone"];  ?>">
            <div class="quantityTime">
              <div class="quantity_container">
                <label for="quantity">Quantity</label>
                <div class="quantity_button">
                  <button type="button" class="minus"> - </button>
                  <input type="number" class="quantity" name="quantity" min="1" value="1" max="<?php echo $data["model_count"];?>">
                  <button type="button" class="plus"> + </button>
                </div>
              </div>
              <div class="time_container">
                <label for="time">Rent time</label>
                <p>in hours</p>
                <div class="time_button">
                  <button type="button" class="minus"> - </button>
                  <input type="number" class="quantity" name="time" min="1" value="1" max="12">
                  <button type="button" class="plus"> + </button>
                </div>
              </div>
            </div>
              <label for="office">Choose location</label>
              <select id="office" name="offfice" required>
                <?php
                  for($i=1;$i<count($data["offices"]);$i++) { ?>
                    <option value="<?php echo $data["offices"][$i];?>" <?php if($_GET["office"] == $i) echo "selected";?>><?php echo ($data["offices"][$i]);?></option>
                  <?php
                  }
                ?>
              </select>
            <div class="terms">
              <input type="checkbox" id="checkbox" required>
              <label for="checkbox">I agree to the <a href="./policy.php">Terms and Conditions</a></label>
            </div>
            <label for="msg">Message</label>
            <textarea name="msg" rows="3" maxlength="255"></textarea>
            <input type="submit" value="Order" id="submit" name="submit">
          </form>
        </div>
      </div>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
