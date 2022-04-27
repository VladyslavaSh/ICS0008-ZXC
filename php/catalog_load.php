<?php
  define("FIELD_BUFFER",4);
  define("PATH_TO_SEARCH_RESULT","./tpl/search_result.php");
  define("PATH_TO_SEARCH_END","./tpl/search_end.php");

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once("./lib/dbconnector.php");
    require_once("./lib/searchTemplate.php");

    if (!empty($_GET["office"])) {
      $office = intval($_GET["office"]);
    } else {
      $office = 0;
    }

    $query_start = "SELECT zxc_model.ID, zxc_model.name,zxc_model.short_description,zxc_model.type,zxc_model.price_hr FROM zxc_model";
    $query_where = "";
    $query_join = "";
    $query_group = " GROUP BY zxc_model.ID";
    $query_args = [];
    if ($office != 0) {
      $query_where = " WHERE zxc_vehicle.office_id = ?";
      $query_args[] = $office;
      $query_join .= " INNER JOIN zxc_vehicle ON zxc_model.ID = zxc_vehicle.model_id";
    }
    if (!empty($_GET["type"])) {
      if (($_GET["type"] == "bike") || ($_GET["type"] == "scooter")) {
        if ($query_where === "") {
          $query_where = " WHERE zxc_model.type = ?";
        } else {
          $query_where .= " AND zxc_model.type = ?";
        }
        if ($_GET["type"] == "bike") {
          $query_args[] = "bike";
        } else {
          $query_args[] = "scooter";
        }
      }
    }
    if (!empty($_GET["priceMin"])) {
      if ($query_where === "") {
        $query_where = " WHERE zxc_model.price_hr > ?";
      } else {
        $query_where .= " AND zxc_model.price_hr > ?";
      }
      $query_args[] = $_GET["priceMin"];
    }
    if (!empty($_GET["priceMax"])) {
      if ($query_where === "") {
        $query_where = " WHERE zxc_model.price_hr < ?";
      } else {
        $query_where .= " AND zxc_model.price_hr < ?";
      }
      $query_args[] = $_GET["priceMax"];
    }
    if (!empty($_GET["search"])) {
      if ($query_where === "") {
        $query_where = " WHERE (zxc_search_tags.tag LIKE CONCAT('%',?,'%') OR zxc_model.name LIKE CONCAT('%',?,'%'))";
      } else {
        $query_where .= " AND (zxc_search_tags.tag LIKE CONCAT('%',?,'%') OR zxc_model.name LIKE CONCAT('%',?,'%'))";
      }
      $query_args[] = strtolower($_GET["search"]);
      $query_args[] = strtolower($_GET["search"]);
      $query_join .= " LEFT JOIN zxc_search_models ON zxc_model.ID = zxc_search_models.model_id LEFT JOIN zxc_search_tags ON zxc_search_models.tag_group = zxc_search_tags.tag_group";
    }
    if (!empty($_GET["filterBy"])) {
      if ($_GET["filterBy"] == "pricea") {
        $query_order = " ORDER BY zxc_model.price_hr ASC";
      } elseif ($_GET["filterBy"] == "priced") {
        $query_order = " ORDER BY zxc_model.price_hr DESC";
      } else {
        $query_order = " ORDER BY zxc_model.total_rented DESC";
      }
    } else {
      $query_order = " ORDER BY zxc_model.total_rented DESC";
    }
    if (!empty($_GET["page"]) && ($_GET["page"] != 0)) {
      $page_num = intval($_GET["page"]);
      $query_limit = " LIMIT ".FIELD_BUFFER." OFFSET ".(FIELD_BUFFER*$page_num);
    } else {
      $query_limit = " LIMIT ".FIELD_BUFFER;
    }

    $template = new SearchTemplate(PATH_TO_SEARCH_RESULT,PATH_TO_SEARCH_END);
    $dbc = new DBConnection();
    $query = $query_start.$query_join.$query_where.$query_group.$query_order.$query_limit;
    $cursor = $dbc->execute($query,$query_args);
    while ($row = $dbc->fetch_one($cursor)) {
      $inner_query_args = [];
      $inner_query = "SELECT COUNT(zxc_vehicle.ID) FROM zxc_vehicle WHERE zxc_vehicle.model_id = ? AND zxc_vehicle.status = 'available'";
      $inner_query_args[] = $row[0]; //ID
      if ($office != 0) {
        $inner_query_where .= " AND zxc_vehicle.office_id = ?";
        $inner_query_args[] = $office;
      }

      $count_cursor = $dbc->execute($inner_query, $inner_query_args);
      echo $template->render($row[0],$row[1],$row[2],$row[3],$row[4],$dbc->fetch_one($count_cursor),$office);
      $dbc->close_cursor($count_cursor);
    }
    if ($cursor->num_rows < FIELD_BUFFER) {
      echo $template->render_end();
    }
    $dbc->close_cursor($cursor);
    unset($dbc);
  }
?>
