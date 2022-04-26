<?php
  define("FIELD_BUFFER",4);
  define("PATH_TO_SEARCH_RESULT","./tpl/search_result.php");
  define("PRODUCT_ALERT_MIN",3);

  class SearchTemplate {
    public $tpl;

    function __construct($path) {
      if (file_exists($path)) {
        $this->tpl = file_get_contents($path);
      } else {
        die("ERROR: Template not found!");
      }
    }

    function render($model_id, $model_name, $model_short_description, $model_type, $model_price, $model_available, $chosen_office) {
      $working_tpl = clone $this->tpl;
      preg_replace("/\{MODEL_NAME}/", $model_name, $working_tpl);
      preg_replace("/\{TYPE}/", ucfirst($model_type), $working_tpl);
      preg_replace("/\{SHORT_DESCRIPTION}/", $model_short_description, $working_tpl);
      preg_replace("/\{PRICE_HR}/", $model_price, $working_tpl);

      if ($model_available == 0) {
        preg_replace("/\{IS_DISABLED}/", 'disabled="disabled"', $working_tpl);
        preg_replace("/\{AVAILABILITY}/", '<p class="empty">There is 0 left!</p>', $working_tpl);
      } else {
        preg_replace("/\{IS_DISABLED}/", "", $working_tpl);
        if ($model_available > PRODUCT_ALERT_MIN) {
          preg_replace("/\{AVAILABILITY}/", "", $working_tpl);
        } else {
          preg_replace("/\{AVAILABILITY}/", '<p class="smallPortion">There is {PRODUCT_NUM} left!</p>', $working_tpl);
          preg_replace("/\{PRODUCT_NUM}/", $model_available, $working_tpl);
        }
      }

      preg_replace("/\{MODEL}/", $model_id, $working_tpl);
      preg_replace("/\{OFFICE}/", $chosen_office, $working_tpl);

      return $working_tpl;
    }
  }


  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once("./lib/dbconnector.php");

    $query_start = "SELECT ID,name,short_description,type,price_hr FROM zxc_model";
    if (empty($_GET["filterBy"] || ($_GET["filterBy"] == "popularity"))) {
      // TODO: FILTER
    }
      // TODO: OTHER FILTERS
    if (!empty($_GET["page"]) && ($_GET["page"] != 0)) {
      $page_num = intval($_GET["page"]);
      $query_limit = " LIMIT ".FIELD_BUFFER." OFFSET ".(FIELD_BUFFER*$page_num);
    } else {
      $query_limit = " LIMIT ".FIELD_BUFFER;
    }
      // TODO: ADD LOGIC

  }
?>
