<?php
  class Post {
    // DB stuff
    private $conn;
    private $table = 'results';

    // Post Properties
    public $resultid;
    public $screenname;
    public $picture;
    public $status;
    public $created_at;
    public $consumerid;
    public $keywordid;
    public $appid;
    public $shid;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Posts
    public function read() {
      // Create query
      $query = 'SELECT * FROM consumer_results';

      // Prepare statement
      $stmt = $this->conn->prepare($query);
      // Execute query
      $stmt->execute();

      return $stmt;
    }
  }
?>
