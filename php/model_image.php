<?php
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_GET["model"])) {
      $model_id = intval($_GET["model"]);
      require_once("./lib/dbconnector.php");

      try {
        $dbc = new DBConnection();
      } catch (Exception $err) {
        echo "Error retrieving the image";
        die();
      }

      $cursor = $dbc->execute("SELECT image FROM zxc_model WHERE id = ?",[$model_id]);
      if ($cursor === FALSE) {
        unset($dbc);
        echo "Error loading the image";
        die();
      }

      if($cursor->num_rows > 0){
        $image_arr = $dbc->fetch_one($cursor);
        header("Content-type: image/png");
        echo $image_arr[0];
      } else {
        echo "Image not found";
      }

      $dbc->close_cursor($cursor);
      unset($dbc);
    }
  }
?>
