<?php

namespace Serwisant\SerwisantApi;

use PDO;

class AccessTokenContainerSqlite implements AccessTokenContainer
{
  private $db;
  private $namespace;
  private $access_token;
  private $expiry_timestamp;

  /**
   * @param $db_name
   * @param string $namespace
   * @throws Exception
   */
  public function __construct($db_name, $namespace = 'default')
  {
    $dir = dirname($db_name);
    if (!is_dir($dir)) {
      throw new Exception("Access token container database directory do not exists - please create '{$dir}' directory.");
    }

    $this->namespace = $namespace;

    $this->db = new PDO("sqlite:" . $db_name);
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->db->exec("CREATE TABLE IF NOT EXISTS access_token (namespace TEXT PRIMARY KEY, token TEXT, expiry INTEGER) WITHOUT ROWID");
  }

  public function store($access_token, $expiry_timestamp)
  {
    $stmt = $this->db->prepare("DELETE FROM access_token WHERE namespace = :namespace");
    $stmt->bindParam(':namespace', $this->namespace);
    $stmt->execute();

    $stmt = $this->db->prepare("INSERT INTO access_token (namespace, token, expiry) VALUES (:namespace, :token, :expiry)");
    $stmt->bindParam(':namespace', $this->namespace);
    $stmt->bindParam(':token', $access_token);
    $stmt->bindParam(':expiry', $expiry_timestamp);
    $stmt->execute();
  }

  public function getAccessToken()
  {
    if ($this->access_token === null) {
      $this->fetchSqlite();
    }
    return $this->access_token;
  }

  public function getExpiryTimestamp()
  {
    if ($this->expiry_timestamp === null) {
      $this->fetchSqlite();
    }
    return (int)$this->expiry_timestamp;
  }

  private function fetchSqlite()
  {
    $stmt = $this->db->prepare('SELECT token, expiry FROM access_token WHERE namespace = :namespace LIMIT 1');
    $stmt->bindParam(':namespace', $this->namespace);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->access_token = $row['token'];
    $this->expiry_timestamp = (int)$row['expiry'];
  }
}
