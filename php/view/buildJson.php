<?php
  function builder() {
    require('../model/db.php');

    $query = "select * from groups";
    $statement = $db->prepare($query);
    $statement->execute();
    $newFeedback = $statement -> fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();

    $file = fopen('../../json/groups.json', 'w');
    fwrite($file, json_encode($newFeedback));
    fclose($file);
  }
?>
