<?php
  session_start();

  function add_entry() {
    require_once("./php/lib/dbconnector.php");
    $dbc = new DBConnection();
    if (email_is_required()) {
      if (!empty($_POST["problemText"])) {
        $dbc->execute("INSERT INTO zxc_support_tickets (email,status,problem_type,problem_header,problem_text) VALUES (?,'awaiting',?,?,?)",[$_POST["email"],$_POST["problemType"],$_POST["problemHeader"],$_POST["problemText"]]);
      } else {
        $dbc->execute("INSERT INTO zxc_support_tickets (email,status,problem_type,problem_header) VALUES (?,'awaiting',?,?)",[$_POST["email"],$_POST["problemType"],$_POST["problemHeader"]]);
      }
    } else {
      $cursor = $dbc->execute("SELECT ID, email FROM zxc_account WHERE email = ?",[$_SESSION["email"]]);
      $row = $dbc->fetch_one($cursor);
      if (!empty($_POST["problemText"])) {
        $dbc->execute("INSERT INTO zxc_support_tickets (email,user_id,status,problem_type,problem_header,problem_text) VALUES (?,?,'awaiting',?,?,?)",[$row[0],$row[1],$_POST["problemType"],$_POST["problemHeader"],$_POST["problemText"]]);
      } else {
        $dbc->execute("INSERT INTO zxc_support_tickets (email,user_id,status,problem_type,problem_header) VALUES (?,?,'awaiting',?,?)",[$row[0],$row[1],$_POST["problemType"],$_POST["problemHeader"]]);
      }
      $dbc->close_cursor($cursor);
    }
    unset($dbc);
  }

  function email_is_invalid($email) {
  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return false;
  }
  return true;
  }

  function email_is_required() {
    if (empty($_SESSION["email"])) {
      return true;
    }
    return false;
  }

  function check_selector($field,$possibleValues) {
    foreach ($possibleValues as $possibleValue) {
      if ($field == $possibleValue) {
        return false;
      }
    }
    return true;
  }

  function field_is_too_long($field,$limit) {
    if (strlen($field) > $limit) {
      return true;
    }
    return false;
  }

  function check_empty(&$postData,$fieldNames) {
    foreach ($fieldNames as $fieldName) {
      if (empty($postData[$fieldName])) {
        return true;
      }
    }
    return false;
  }

  function check_fields() {
    if (check_empty($_POST,["problemType","problemHeader"])) return 1;
    if (email_is_required()) {
      if (empty($_POST["email"])) return 1;
      if (email_is_invalid($_POST["email"])) return 2;
      if (field_is_too_long($_POST["email"],255)) return 3;
    }
    if (check_selector($_POST["problemType"],["rent_mistake","payment","refund","bug","service","other"])) return 4;
    if (field_is_too_long($_POST["problemHeader"],50)) return 3;
    if (!empty($_POST["problemText"])) {
      if (field_is_too_long($_POST["problemText"],10000)) return 3;
    }
    return 0;
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
  } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errno = check_fields();
    if ($errno == 0) {
      add_entry();
      header("Location: ./support.php?message=success");
    }
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "./php/tpl/head.php"; ?>
    <title>Support - ZXC</title>
    <link rel="stylesheet" href="./css/support.css">
  </head>
  <body>
    <?php include "./php/tpl/navbar.php"; ?>
    <div class="main">
      <div class="form">
        <?php
          if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!empty($_GET["message"])) {
              if ($_GET["message"] == "success") {
                echo '<div id="successField"><p>Your ticket has been created successfully!</p></div>';
              }
            }
          } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($errno)) {
              if ($errno !== 0) {
                echo '<div id="errorField">';
                if ($errno == 1) {
                  echo "<p>Please fill required fields!</p>";
                } elseif ($errno == 2) {
                  echo "<p>The email you inputed is invalid!</p>";
                } elseif ($errno == 3) {
                  echo "<p>You have inputed too many characters!</p>";
                } elseif ($errno == 4) {
                  echo "<p>You have not selected the problem's type</p>";
                }
                echo '</div>';
              }
            }
          }
        ?>
        <form class="" action="./support.php" method="post">
          <?php if(empty($_SESSION["email"])) {
            echo '<label for="emailInput">Your email</label><br>';
            echo '<input type="email" id="emailInput" name="email" value="" placeholder="you@example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" maxlength="255" required>';
            echo '<abbr title="This information is needed to contact you">?</abbr>';
          } ?>
          <br>
          <label for="problemType">What type of problem have occured with you?</label>
          <br>
          <select id="problemType" name="problemType" required>
            <option value="">Please select one</option>
            <option value="rent_mistake">I have rented a vehicle by mistake</option>
            <option value="payment">I have some difficulties with payment system</option>
            <option value="refund">I would want to have a refund</option>
            <option value="bug">I have noticed a bug on this website</option>
            <option value="service">I have some troubles with your service</option>
            <option value="other">My problem is not in this list</option>
          </select>
          <br>
          <label for="problemHeader">What this problem is about?</label>
          <br>
          <input id="problemHeader" type="text" name="problemHeader" value="" maxlength="50" size="60" required>
          <br>
          <label for="problemText">Please describe it fully</label>
          <br>
          <textarea id="problemText" name="problemText" rows="8" cols="80" maxlength="10000"></textarea>
          <br>
          <input type="checkbox" id="agreeBox" required>
          <label for="agreeBox">I have read the <a href="./policy.php">Terms and Conditions</a></label>
          <br>
          <input type="submit" name="" value="Submit">
        </form>
      </div>
    </div>
    <?php include "./php/tpl/footer.php"; ?>
  </body>
</html>
