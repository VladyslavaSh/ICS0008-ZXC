<?php
  // Database Mysql connector class
  class DBConnection {
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;

    public $connection;

    function __construct($serverhost = "", $username = "", $password = "", $database = "", $serverport = 3306) {
      // Config

      $default_host = "anysql.itcollege.ee";
      $default_username = "ICS0008_WT_2";
      $default_password = "6ec9e61f3528";
      $default_database = "ICS0008_2";

      // Applying config

      if (empty($serverhost)) {
        $this->host = $default_host;
      } else {
        $this->host = $serverhost;
      }
      if (empty($username)) {
        $this->username = $default_username;
      } else {
        $this->username = $username;
      }
      if (empty($password)) {
        $this->password = $default_password;
      } else {
        $this->password = $password;
      }
      if (empty($database)) {
        $this->database = $default_database;
      } else {
        $this->database = $database;
      }
      $this->port = $serverport;

      // Creating a connection

      $this->connection = new mysqli($this->host,$this->username,$this->password,$this->database, $this->port);

      if ($this->connection->connect_error) {
        throw new Exception("ConnectError: ".$this->connection->connect_error, 2001);
      }
    }

    function __destruct() {
      $this->connection->close();
    }

    function execute($query,$args = []) {

      // Executes a query. In addition an args can be passed in.
      // Returns: FALSE, if execute failed; TRUE if execute is successfull; CURSOR OBJECT if SELECT/FETCH is successfull.
      // Exapmle: $dbc->execute("INSERT INTO my_table(name,age) VALUES (?,?)",("John Smith",36));
      //          >>> TRUE
      // This function returns TRUE if the command execution completed successfully. Else it if False
      //
      // Attention! This function doesn't process blob types!

      if ($this->connection->connect_error) {
        return FALSE;
      }
      if (!empty($args)) {
        $stmt = $this->connection->prepare($query);
        $mysql_types = "";
        foreach ($args as $value) {
          if (gettype($value) === "integer") {
            $mysql_types .= "i";
          } elseif (gettype($value) === "double") {
            $mysql_types .= "d";
          } elseif (gettype($value) === "string") {
            $mysql_types .= "s";
          } else {
            return FALSE;
          }
        }
        $stmt->bind_param($mysql_types,...$args);
        $result = $stmt->execute();
        if ($result === FALSE) {
          $cursor = FALSE;
        } else {
          $cursor = $stmt->get_result();
        }
        if ($cursor !== FALSE) {
          $result = $cursor;
        }
        $stmt->close();
      } else {
        $result = $this->connection->query($query);
      }
      return $result;
    }

    function get_error() {
      // In case the query fails to execute, you can use this function to receive last error
      return $this->connection->error;
    }

    function fetch_one(&$cursor) {
      // Returns a numbered array for from one row in result
      return $cursor->fetch_row();
    }

    function fetch_all(&$cursor) {
      // Returns a nested numbered array from all rows in result
      return $cursor->fetch_all(MYSQLI_NUM);
    }

    function close_cursor(&$cursor) {
      // Closes the cursor and frees the memory
      $cursor->close();
    }
  }
?>
