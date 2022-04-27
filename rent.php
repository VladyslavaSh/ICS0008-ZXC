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

    require "db_connect.php";
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

    function set_fields(&$fields){
      foreach($fields as $field) {
          if(!isset($_GET[$field])) {
            $_GET[$field] = "";
          }
      }
    }

    set_fields($fields);

    function required_check(&$required){
      foreach($required as $field) {
          if(empty(trim($_GET[$field]))) {
              return 1;
          }
      }
    }

    if(required_check($required)) redirect();

    $model = $_GET["model"];
    $office_ID = $_GET["office"];
    $data = [];
    
    function office_check($link, $office_ID) {
      $query = "SELECT ID, name, address, post FROM zxc_offices WHERE ID=?";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "i", $office_ID);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
  
      if(mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $office_id, $office_name, $address, $post);
        mysqli_stmt_fetch($stmt);
        $data = array("office_id" => $office_id, "office_name" => $office_name, "address" => $address, "post" => $post);
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
      while(mysqli_stmt_fetch($stmt)) {
        array_push($data, $office_name);
      }
      return $data;
    }

    $offices_data = offices_get($link);
    $data["offices"] = $offices_data;

    function model_check($link, $model) {
      $query = "SELECT ID, name, description, price_hr, image FROM zxc_model WHERE ID=?";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "i", $model);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);

      if(mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $model_id, $model_name, $description, $price, $img);
        mysqli_stmt_fetch($stmt);
      $data = array("model_id" => $model_id, "model_name" => $model_name, "description" => $description, "price" => $price, "img" => $img);
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
      $query = "SELECT COUNT(model_id), office_id FROM zxc_vehicle WHERE model_id=? AND status='available' GROUP BY office_id";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "i", $model);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $model_count, $model_count_office_id);
      mysqli_stmt_store_result($stmt);
      if(mysqli_stmt_num_rows($stmt) > 0) {
        $data = [];
        while(mysqli_stmt_fetch($stmt)) {
          $data[$model_count_office_id] = $model_count;
        }
        return $data;
      }
      else {
        return 1;
      }
    }

    $model_count_data = model_count_check($link, $model);
    if ($model_count_data != 1) $data["model_count"] = $model_count_data;

    function user_data_check($link) {
      $query = "SELECT ID, first_name, last_name, phone FROM zxc_account WHERE email=?";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "s", $_SESSION["email"]);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);

      if(mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $ID, $first_name, $last_name, $phone);
        mysqli_stmt_fetch($stmt);
        $data = array("account_id" => $ID, "first_name" => $first_name, "last_name" => $last_name, "phone" => $phone);
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

  // VALIDATION TIME
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    function validation($data){



      $fields = ["nameFirst", "nameLast", "phone", "quantity", "time", "office", "checkbox", "msg"];
      $required = ["quantity", "time", "office", "checkbox"];

      if(!isset($_POST["submit"]) || empty($_POST["submit"])) return 1;
      if($_POST["submit"] != "Order") return 2;

      function isset_check(&$fields){
        foreach($fields as $field) {
          if(!isset($_POST[$field])) {
            return 1;
          }
        }
      }

      if(isset_check($fields)) return 3;

      function empty_check(&$required){
        foreach($required as $field) {
          if(empty(trim($_POST[$field]))) {
            return 1;
          }
        }
      }

      if(empty_check($required)) return 4;

      function html_inj(&$fields) {
        foreach($fields as $field) {
          $clear = strip_tags($_POST[$field]);
          if($clear != $_POST[$field]) {
            return 1;
          }
        }
      }

      if(html_inj($fields)) return 5;

      $first_name = $_POST["nameFirst"];
      $last_name = $_POST["nameLast"];
      $phone = $_POST["phone"];
      $quantity = $_POST["quantity"];
      $time = $_POST["time"];
      $office = $_POST["office"];
      $msg = $_POST["msg"];


      function validate_names($list){
        foreach($list as $str){
          if (preg_match('/[0-9\@\:\.\;\|\!\#\}\$\{\=\\&\*\(\)\+\_\%\" "]+/', $str) || (strlen($str) > 20))
          {
            return 1;
          }
        }
      }

      if(validate_names([$first_name, $last_name])) return 6;

      function phone_check(&$phone) {
        if(strlen($phone) > 14) return 1;
        if(preg_match('/[^0-9\+]/', $phone)) return 1;
      }

      if(phone_check($phone)) return 7;

      function office_arr_check(&$office_to_check, &$offices) {
        $data = [];
        for($i=0;$i<count($offices);$i++) {
          if($offices[$i] == $office_to_check) {
            array_push($data, $i+1);
            return $data;
          }
        }
        return 1;
      }

      $office_id_arr = office_arr_check($office, $data["offices"]);
      if ($office_id_arr == 1) return 10;
      $office_id = $office_id_arr[0];

      function quantity_check(&$quantity, $count) {
        if($quantity < 1 || $quantity > $count) return 1;
      }

      if(!isset($data["model_count"][$office_id])) return 12;

      if(quantity_check($quantity, $data["model_count"][$office_id])) return 8;

      function time_check(&$time) {
        if($time < 1 || $time > 12) return 1;
      }

      if(time_check($time)) return 9;

      function msg_check(&$msg) {
        if(strlen($msg) > 255) return 1;
      }

      if(msg_check($msg)) return 11;

      require "db_connect.php";
      $link = mysqli_connect($server, $user, $pass, $db);
      if (!$link) {
        die("Connection failed: ".mysqli_connect_error());
      }

      // adding order to zxc_previous_orders table
      $query = "INSERT INTO zxc_previous_orders (first_name, last_name, phone, amount, message, office_id, account_ID, model_id, rented_until, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL ? HOUR), 'completed')";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "ssiisiiis", $first_name, $last_name, $phone, $quantity, $msg, $office_id, $data["account_id"], $data["model_id"], $time);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      // updating a vehicle of the first available zxc_vehicle table
      $query = "UPDATE zxc_vehicle SET status = 'rented', rented_by=?, rented_when=NOW(), rented_until=date_add(NOW(), INTERVAL ? hour) WHERE model_id=? AND status = 'available' LIMIT ?";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "sssi", $data["account_id"], $time, $data["model_id"], $quantity);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      // incrementing toral_rented in zxc_model table
      $query = "UPDATE zxc_model SET total_rented=total_rented + ? WHERE id=?";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "ii", $quantity, $data["model_id"]);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);

      mysqli_close($link);
      header("Location: account.php");
      die();
      return 0;
    }
    $result = validation($data);
  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <link rel="stylesheet" href="./css/rent.css">
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        let amount;
        function change() {
          <?php
            $JSON = json_encode($data["offices"]);
            $JSON2 = json_encode($data["model_count"]);
          ?>

          let stock = document.getElementById("stock");
          let quantity = document.getElementsByClassName("quantity_button")[0].children[1];
          let officeIDs = <?php echo $JSON2;?>;
          let officeNames = <?php echo $JSON;?>;

          for (let i=0;i<officeNames.length;i++) {
            if(officeNames[i] === this.value) {
              if(officeIDs[i+1]) {
                amount = officeIDs[i+1];
                stock.style.color = "black";
                stock.innerHTML = "In stock: "+amount;
                quantity.setAttribute("max", amount);
                quantity.value = 1;
              }
              else{
                stock.style.color = "red";
                stock.innerHTML = "Out of stock";
                quantity.setAttribute("max", 0);
              }
            }
          }
        }
        document.getElementById("office").addEventListener("change", change);

        // COUNTER + -
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
            if(quantity+1 <= amount) {
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
      <?php
          if($_SERVER["REQUEST_METHOD"] == "POST"){
            if ($result != 0) echo '<div class="formResult">';
            if ($result == 1){
              echo "Submit button name was changed";
            }
            elseif ($result  == 2){
              echo "Value of the submit button was changed";
            }
            elseif ($result == 3){
              echo "Some of the fields were deleted";
            }
            elseif ($result == 4){
              echo "Some of the required fields were empty";
            }
            elseif ($result == 5){
              echo "No html tags allowed";
            }
            elseif ($result == 6){
              echo "Name can contain only - and ' from special characters and at most 20 characters long";
            }
            elseif ($result == 7){
              echo "Only digits and + are allowed for phone";
            }
            elseif ($result == 8){
              echo "Quantity cannot be less than 1 or more than in stock";
            }
            elseif ($result == 9){
              echo "Time cannot be less than 1 or more than 12";
            }
            elseif ($result == 10){
              echo "Office name was altered";
            }
            elseif ($result == 11){
              echo "Message cannot be more than 255 chars long";
            }
            elseif ($result == 12){
              echo "Out of stock";
            }
            if ($result != 0) echo "</div>";
          }
        ?>
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
                <p id="stock" <?php if(!isset($data["model_count"][$data["office_id"]])) echo "style=color:red;"?>>
                <?php
                  if(isset($data["model_count"][$data["office_id"]])) {
                    echo "In stock: ".$data["model_count"][$data["office_id"]];
                  }
                  else {
                    echo "Out of stock";
                  }

                ?>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="order_form">
          <form action="<?php echo (basename($_SERVER['REQUEST_URI'])) ?>" method="post">
            <label for="nameFirst" id="top_label">First name</label>
            <input type="text" id="NameFirst" name="nameFirst" pattern="^[A-Za-zÀ-ÿа-яА-ЯёЁ,.'-]+$" maxlength="20" value="<?php echo $data["first_name"];  ?>">
            <label for="NameLast">Last name</label>
            <input type="text" id="NameLast" name="nameLast" pattern="^[A-Za-zÀ-ÿа-яА-ЯёЁ,.'-]+$" maxlength="20" value="<?php echo $data["last_name"];  ?>">
            <label for="phone">Phone</label>
            <input type="tel" pattern="[+0-9]*" id="phone" name="phone" maxlength="14" value="<?php echo $data["phone"];  ?>">
            <div class="quantityTime">
              <div class="quantity_container">
                <label for="quantity">Quantity</label>
                <div class="quantity_button">
                  <button type="button" class="minus"> - </button>
                  <input type="number" class="quantity" name="quantity" min="1" value="1" max="<?php
                  if(isset($data["model_count"][$data["office_id"]])) {
                    echo $data["model_count"][$data["office_id"]];
                  }
                  else {
                    echo "0";
                  }
                  ?>">
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
              <select id="office" name="office" required>
                <?php
                  for($i=0;$i<count($data["offices"]);$i++) { ?>
                    <option value="<?php echo $data["offices"][$i];?>" <?php if($_GET["office"] == $i+1) echo "selected";?>><?php echo ($data["offices"][$i]);?></option>
                  <?php
                  }
                ?>
              </select>
            <div class="terms">
              <input type="checkbox" id="checkbox" name="checkbox" required>
              <label for="checkbox">I agree to the <a href="./policy.php">Terms and Conditions</a></label>
            </div>
            <label for="msg">Message (max 255 chars)</label>
            <textarea name="msg" rows="3" maxlength="255"></textarea>
            <input type="submit" value="Order" id="submit" name="submit">
          </form>
        </div>
      </div>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
