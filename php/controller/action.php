<?php
  require('../model/db.php');
  $action = $_POST['action'];

  if ($action == 'done') {
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
      $statement->closeCursor();
    }

    setcookie('fName', $fName, time()+18000, '/', 'localhost', false, true);
    setcookie('lName', $lName, time()+18000, '/', 'localhost', false, true);
    setcookie('bDate', $bDate, time()+18000, '/', 'localhost', false, true);
    header('Location: ../view/groups.php');
  } else if ($action == 'addGroup') {
    $query = 'select * from groups where name = :name';
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $_POST['Name']);
    $statement->execute();
    $newFeedback = $statement -> fetch();
    $statement->closeCursor();

    if ($newFeedback == 0) {
      $query = 'INSERT INTO groups (name, date_added)
      VALUES (:name, :added)';
      $statement = $db->prepare($query);
      $statement->bindValue(':name', $_POST['Name']);
      $statement->bindValue(':added', date('Y-m-d H:i:s'));
      $statement->execute();
      $statement->closeCursor();

      $query = "select * from person where ((fname = :fName) && (lname = :lName) && (birthday = :bDate))";
      $statement = $db->prepare($query);
      $statement->bindValue(':fName', $_COOKIE['fName']);
      $statement->bindValue(':lName', $_COOKIE['lName']);
      $statement->bindValue(':bDate', $_COOKIE['bDate']);
      $statement->execute();
      $curUser = $statement -> fetch();
      $statement->closeCursor();

      $query = "select * from groups where name = :name";
      $statement = $db->prepare($query);
      $statement->bindValue(':name', $_POST['Name']);
      $statement->execute();
      $curGroup = $statement -> fetch();
      $statement->closeCursor();

      $query = 'INSERT INTO poeple_group (groups_id, person_id, posted)
      VALUES (:g_id, :p_id, :post)';
      $statement = $db->prepare($query);
      $statement->bindValue(':g_id', $curGroup['groups_id']);
      $statement->bindValue(':p_id', $curUser['person_id']);
      $statement->bindValue(':post', date('Y-m-d H:i:s'));
      $statement->execute();
      $statement->closeCursor();
    }

    header('Location: ../view/groups.php');
  } else if ($action == 'addUsersToGroup') {
    $query = 'select * from poeple_group where (groups_id = :group) && (person_id = :person)';
    $statement = $db->prepare($query);
    $statement->bindValue(':group', $_POST['selectGroup']);
    $statement->bindValue(':person', $_POST['selectUser']);
    $statement->execute();
    $newFeedback = $statement -> fetch();
    $statement->closeCursor();

    if ($newFeedback == 0) {
      $query = 'INSERT INTO poeple_group (groups_id, person_id, posted) values
      (:group, :person, :post)';
      $statement = $db->prepare($query);
      $statement->bindValue(':group', $_POST['selectGroup']);
      $statement->bindValue(':person', $_POST['selectUser']);
      $statement->bindValue(':post', date('Y-m-d H:i:s'));
      $statement->execute();
      $statement->closeCursor();
    }

    header('Location: ../view/groups.php');
  } else if ($action == 'seeWhoPosts') {

    setcookie('name', $_POST['whoPost'], time()+18000, '/', 'localhost', false, true);
    header('Location: ../view/posts.php');
  } else if ($action == 'addPost') {
    $query = 'SELECT person.person_id, groups.groups_id
    from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
    where ((fname = :fname) && (lname = :lname) && (birthday = :birthday) && (name = :name))';
    $statement = $db->prepare($query);
    $statement->bindValue(':fname', $_COOKIE['fName']);
    $statement->bindValue(':lname', $_COOKIE['lName']);
    $statement->bindValue(':birthday', $_COOKIE['bDate']);
    $statement->bindValue(':name', $_COOKIE['name']);
    $statement->execute();
    $newFeedback = $statement -> fetch();
    $statement->closeCursor();

    $query = 'INSERT INTO poeple_group (groups_id, person_id, message, posted)
    VALUES (:groups_id, :person_id, :message, :posted)';
    $statement = $db->prepare($query);
    $statement->bindValue(':groups_id', $newFeedback['groups_id']);
    $statement->bindValue(':person_id', $newFeedback['person_id']);
    $statement->bindValue(':message', $_POST['message']);
    $statement->bindValue(':posted', date('Y-m-d H:i:s'));
    $statement->execute();
    $newFeedback = $statement -> fetch();
    $statement->closeCursor();

    header('Location: ../view/posts.php');
  } else if ($action == 'logOut') {
    $_COOKIE['fName'] = null;
    $_COOKIE['lName'] = null;
    $_COOKIE['bDate'] = null;

    setcookie('fName', null, -1, '/');
    setcookie('lName', null, -1, '/');
    setcookie('bDate', null, -1, '/');


    if(isset($_COOKIE['name']) == true) {
      $_COOKIE['name'] = null;
      setcookie('name', null, -1, '/');
    }

    if(isset($_COOKIE['adim']) == true) {
      $_COOKIE['adim'] = null;
      setcookie('adim', null, -1, '/');
    }

    header('Location: ../../index.php');
  } else if($action == 'giveFeedback') {
    $query = 'SELECT *
     from person
     where ((fname = :fname) && (lname = :lname) && (birthday = :birthday))
     LIMIT 1';
    $statement = $db->prepare($query);
    $statement->bindValue(':fname', $_COOKIE['fName']);
    $statement->bindValue(':lname', $_COOKIE['lName']);
    $statement->bindValue(':birthday', $_COOKIE['bDate']);
    $statement->execute();
    $newFeedback = $statement -> fetch();
    $statement->closeCursor();

    if($_POST['selectGroupFeedback'] == -1) {
      $query = 'INSERT INTO feedback (message, rating, person_id, placed)
        VALUES (:message, :rating, :person_id, :placed)';
      $statement = $db->prepare($query);
      $statement->bindValue(':message', $_POST['txtMessage']);
      $statement->bindValue(':rating', $_POST['rating']);
      $statement->bindValue(':person_id', $newFeedback['person_id']);
      $statement->bindValue(':placed', date('Y-m-d H:i:s'));
      $statement->execute();
      $statement->closeCursor();
    } else {
      $query = 'INSERT INTO feedback (message, rating, person_id, groups_id, placed)
        VALUES (:message, :rating, :person_id, :groups_id, :placed)';
      $statement = $db->prepare($query);
      $statement->bindValue(':message', $_POST['txtMessage']);
      $statement->bindValue(':rating', $_POST['rating']);
      $statement->bindValue(':person_id', $newFeedback['person_id']);
      $statement->bindValue(':groups_id', $_POST['selectGroupFeedback']);
      $statement->bindValue(':placed', date('Y-m-d H:i:s'));
      $statement->execute();
      $statement->closeCursor();
    }

    header('Location: ../view/groups.php');
  } else if($action == 'adim') {
    $query = 'SELECT *
     from adims
     where adim_key = :key';
    $statement = $db->prepare($query);
    $statement->bindValue(':key', $_POST['adimString']);
    $statement->execute();
    $newFeedback = $statement -> fetch();
    $statement->closeCursor();

    if($newFeedback != 0) {
      setcookie('adim', 'cVU7k1hstJ', time()+18000, '/', 'localhost', false, true);
    }

    header('Location: ../view/groups.php');
  } else if($action == 'delateGroup') {
    $query = 'DELETE FROM poeple_group WHERE groups_id = :gID';
    $statement = $db->prepare($query);
    $statement->bindValue(':gID', $_POST['groupID']);
    $statement->execute();
    $statement->closeCursor();

    $query = 'DELETE FROM groups WHERE groups_id = :gID';
    $statement = $db->prepare($query);
    $statement->bindValue(':gID', $_POST['groupID']);
    $statement->execute();
    $statement->closeCursor();

    header('Location: ../view/groups.php');
  } else if($action == 'delatePost') {
    $query = 'DELETE FROM poeple_group WHERE ((message = :messagePG) && (posted = :postedPG))';
    $statement = $db->prepare($query);
    $statement->bindValue(':postedPG', $_POST['postedPG']);
    $statement->bindValue(':messagePG', $_POST['messagePG']);
    $statement->execute();
    $statement->closeCursor();

    header('Location: ../view/posts.php');
  }
?>
