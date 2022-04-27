<?php
define("PRODUCT_ALERT_MIN",3);

class SearchTemplate {
  public $tpl;
  public $endTpl;

  function __construct($path,$endPath) {
    if (file_exists($path)) {
      $this->tpl = file_get_contents($path);
    } else {
      die("ERROR: Template not found!");
    }
    if (file_exists($endPath)) {
      $this->endTpl = file_get_contents($endPath);
    } else {
      die("ERROR: Template not found!");
    }
  }

  function render($model_id, $model_name, $model_short_description, $model_type, $model_price, $model_available, $chosen_office = 0) {
    $working_tpl = $this->tpl;
    $working_tpl = str_replace("{MODEL_NAME}", $model_name, $working_tpl);
    $working_tpl = str_replace("{TYPE}", ucfirst($model_type), $working_tpl);
    $working_tpl = str_replace("{SHORT_DESCRIPTION}", $model_short_description, $working_tpl);
    $working_tpl = str_replace("{PRICE_HR}", $model_price, $working_tpl);

    if ($model_available == 0) {
      $working_tpl = str_replace("{IS_DISABLED}", 'disabled="disabled"', $working_tpl);
      $working_tpl = str_replace("{AVAILABILITY}", '<p class="empty">There is 0 left!</p>', $working_tpl);
    } else {
      $working_tpl = str_replace("{IS_DISABLED}", "", $working_tpl);
      if ($model_available > PRODUCT_ALERT_MIN) {
        $working_tpl = str_replace("{AVAILABILITY}", "", $working_tpl);
      } else {
        $working_tpl = str_replace("{AVAILABILITY}", '<p class="smallPortion">There is {PRODUCT_NUM} left!</p>', $working_tpl);
        $working_tpl = str_replace("{PRODUCT_NUM}", $model_available, $working_tpl);
      }
    }

    $working_tpl = str_replace("{MODEL}", $model_id, $working_tpl);

    if ($chosen_office == 0) {
      $working_tpl = str_replace("{OFFICE}", "", $working_tpl);
    } else {
      $working_tpl = str_replace("{OFFICE}", '<input type="office" name="office" value="{OFFICE_NUM}" hidden>', $working_tpl);
      $working_tpl = str_replace("{OFFICE_NUM}", $chosen_office, $working_tpl);
    }

    return $working_tpl;
  }

  function render_end() {
    return $this->endTpl;
  }
}
?>
