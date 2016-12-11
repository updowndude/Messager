<?php
  // by Correy Winke
  // 10/27/16
  // opens up a database
  $dsn = 'mysql:host=localhost;dbname=cwinkebt_Messenger';
  $username = 'root';
  $password = 'root';
  $error = '';

  // check to se it works
  try {
      $db = new PDO($dsn, $username, $password);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $err) {
    exit($err->getMessage());
  }
?>
