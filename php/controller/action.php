<?php
  require_once('../model/db.php');
  $action = $_POST['action'];

  if ($action = 'done') {
    $fName = addslashes(htmlspecialchars($_POST['fName']));
    $lName = addslashes(htmlspecialchars($_POST['lName']));
    $bDate = date('Y-m-d' ,htmlspecialchars(strtotime($_POST['bDate'])));

    $query = 'SELECT *
     from person
     where ((fname = :fname) && (lname = :lname) && (birthday = :birthday))
     LIMIT 1';

    $statement = $db->prepare($query);
    $statement->bindValue(':fname', $fName);
    $statement->bindValue(':lname', $lName);
    $statement->bindValue(':birthday', $bDate);
    $statement->execute();
    $newFeedback = $statement -> fetch();
    $statement->closeCursor();

    if ($newFeedback == 0) {
      $query = 'INSERT INTO person (fname, lname, birthday)
      VALUES (:fname, :lname, :birthday)';

      $statement = $db->prepare($query);
      $statement->bindValue(':fname', $fName);
      $statement->bindValue(':lname', $lName);
      $statement->bindValue(':birthday', $bDate);
      $statement->execute();
      $newFeedback = $statement -> fetch();
      $statement->closeCursor();
    }
  }
?>
