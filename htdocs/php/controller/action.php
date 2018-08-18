<?hh
  function main() {
    require('defense.php');

    !isset($_SESSION) ? session_start() : null;

    (($_SERVER['REQUEST_METHOD'] === 'POST') && (checkToken() == true)) ? actions() : exit('Something happened');
  }

  function actions() {
    require('../model/db.php');
    require('../view/user.php');

    $aryMyValues = allowedValues([
      'action','fName','lName','bDate','Name','selectGroup','selectUser','whoPost','message','txtMessage','rating','selectGroupFeedback','adimString','groupID','postedPG','messagePG'
    ]);

    $action = $aryMyValues['action'];

    if ($action == 'done') {
      $fName = $aryMyValues['fName'];
      $lName = $aryMyValues['lName'];
      $bDate = date('Y-m-d', strtotime($aryMyValues['bDate']));

      $query = 'SELECT *
       from person
       where ((fname = :fname) && (lname = :lname) && (birthday = :birthday))
       LIMIT 1';

      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':fname', $fName);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':lname', $lName);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':birthday', $bDate);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $newFeedback = $statement -> fetch();
      $statement->closeCursor();

      if ($newFeedback == 0) {
        $query = 'INSERT INTO person (fname, lname, birthday)
        VALUES (:fname, :lname, :birthday)';

        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $bind_results = $statement->bindValue(':fname', $fName);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':lname', $lName);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':birthday', $bDate);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $statement->closeCursor();
      }

      $_SESSION['fName'] = $fName;
      $_SESSION['lName'] = $lName;
      $_SESSION['bDate'] = $bDate;

      header('Location: ../view/groups.php');
    } else if ($action == 'addGroup') {
      $query = 'select * from groups where name = :name';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':name', $aryMyValues['Name']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $newFeedback = $statement -> fetch();
      $statement->closeCursor();

      if ($newFeedback == 0) {
        $query = 'INSERT INTO groups (name, date_added)
        VALUES (:name, :added)';
        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $bind_results = $statement->bindValue(':name', $aryMyValues['Name']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':added', date('Y-m-d H:i:s'));
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $statement->closeCursor();

        $query = "select * from person where ((fname = :fName) && (lname = :lName) && (birthday = :bDate))";
        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $bind_results = $statement->bindValue(':fName', $_SESSION['fName']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':lName', $_SESSION['lName']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':bDate', $_SESSION['bDate']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $curUser = $statement -> fetch();
        $statement->closeCursor();

        $query = "select * from groups where name = :name";
        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $bind_results = $statement->bindValue(':name', $aryMyValues['Name']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $curGroup = $statement -> fetch();
        $statement->closeCursor();

        $query = 'INSERT INTO poeple_group (groups_id, person_id, posted)
        VALUES (:g_id, :p_id, :post)';
        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $bind_results = $statement->bindValue(':g_id', $curGroup['groups_id']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':p_id', $curUser['person_id']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':post', date('Y-m-d H:i:s'));
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $statement->closeCursor();
      }

      header('Location: ../view/groups.php');
    } else if ($action == 'addUsersToGroup') {
      $query = 'select * from poeple_group where (groups_id = :group) && (person_id = :person)';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':group', $aryMyValues['selectGroup']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':person', $aryMyValues['selectUser']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $newFeedback = $statement -> fetch();
      $statement->closeCursor();

      if ($newFeedback == 0) {
        $query = 'INSERT INTO poeple_group (groups_id, person_id, posted) values
        (:group, :person, :post)';
        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $bind_results = $statement->bindValue(':group', $aryMyValues['selectGroup']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':person', $aryMyValues['selectUser']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':post', date('Y-m-d H:i:s'));
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $statement->closeCursor();
      }

      header('Location: ../view/groups.php');
    } else if ($action == 'seeWhoPosts') {
      $_SESSION['name'] = $aryMyValues['whoPost'];
      header('Location: ../view/posts.php');
    } else if ($action == 'addPost') {
      $query = 'SELECT person.person_id, groups.groups_id
      from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
      where ((fname = :fname) && (lname = :lname) && (birthday = :birthday) && (name = :name))';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':fname', $_SESSION['fName']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':lname', $_SESSION['lName']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':birthday', $_SESSION['bDate']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':name', $_SESSION['name']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $newFeedback = $statement -> fetch();
      $statement->closeCursor();

      if(isset($_FILES['userPostVideo']) == true) {
        $file = $_FILES['userPostVideo'];
        $allowedExistion = array('mp4');
        $fileNameArry = explode('.', $file['name']);
        $fileName = $file['name'];
        $curDateTime = date("Y-m-d H:i:s");

        if(($file['size'] <= 20000000) && (end($fileNameArry) == 'mp4') && (strlen($fileName) <= 7) && ($file['error']) == 0) {
          $fileNewName = "{$fileNameArry[0]}{$curDateTime}.{$fileNameArry[1]}";
          $query = 'INSERT INTO poeple_group (groups_id, person_id, message, posted, video)
          VALUES (:groups_id, :person_id, :message, :posted, :file)';
          $statement = $db->prepare($query);
          if (!$statement) {
            exit("Sorry prepare failed");
          }
          $bind_results = $statement->bindValue(':file', $fileNewName);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':groups_id', $newFeedback['groups_id']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':person_id', $newFeedback['person_id']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':message', $aryMyValues['message']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':posted', date('Y-m-d H:i:s'));
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $workQuery = $statement->execute();
          if(!$workQuery) {
            exit("Bad execcution");
          }
          $statement->closeCursor();
          move_uploaded_file($file['tmp_name'], '../../uploads/'.$fileNewName);
        } else {
          $query = 'INSERT INTO poeple_group (groups_id, person_id, message, posted)
          VALUES (:groups_id, :person_id, :message, :posted)';
          $statement = $db->prepare($query);
          if (!$statement) {
            exit("Sorry prepare failed");
          }
          $bind_results = $statement->bindValue(':groups_id', $newFeedback['groups_id']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':person_id', $newFeedback['person_id']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':message', $aryMyValues['message']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':posted', date('Y-m-d H:i:s'));
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $workQuery = $statement->execute();
          if(!$workQuery) {
            exit("Bad execcution");
          }
          $statement->closeCursor();
        }
      } else {
        $query = 'INSERT INTO poeple_group (groups_id, person_id, message, posted)
        VALUES (:groups_id, :person_id, :message, :posted)';
        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $bind_results = $statement->bindValue(':groups_id', $newFeedback['groups_id']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':person_id', $newFeedback['person_id']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':message', $aryMyValues['message']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':posted', date('Y-m-d H:i:s'));
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $statement->closeCursor();
      }

      header('Location: ../view/posts.php');
    } else if ($action == 'logOut') {
      session_unset();
     	session_destroy();

      header('Location: ../../index.php');
    } else if($action == 'giveFeedback') {
      $query = 'SELECT *
       from person
       where ((fname = :fname) && (lname = :lname) && (birthday = :birthday))
       LIMIT 1';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':fname', $_SESSION['fName']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':lname', $_SESSION['lName']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':birthday', $_SESSION['bDate']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $newFeedback = $statement -> fetch();
      $statement->closeCursor();

      if($aryMyValues['selectGroupFeedback'] == -1) {
        $query = 'INSERT INTO feedback (message, rating, person_id, placed)
          VALUES (:message, :rating, :person_id, :placed)';
        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $bind_results = $statement->bindValue(':message', $aryMyValues['txtMessage']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':rating', $aryMyValues['rating']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':person_id', $newFeedback['person_id']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':placed', date('Y-m-d H:i:s'));
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $statement->closeCursor();
      } else {
        $query = 'INSERT INTO feedback (message, rating, person_id, groups_id, placed)
          VALUES (:message, :rating, :person_id, :groups_id, :placed)';
        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $bind_results = $statement->bindValue(':message', $aryMyValues['txtMessage']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':rating', $aryMyValues['rating']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':person_id', $newFeedback['person_id']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':groups_id', $aryMyValues['selectGroupFeedback']);
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $bind_results = $statement->bindValue(':placed', date('Y-m-d H:i:s'));
        if(!$bind_results) {
          exit("Sorry can't bind those value");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $statement->closeCursor();
      }

      header('Location: ../view/groups.php');
    } else if($action == 'adim') {
      $query = 'SELECT *
       from adims
       where adim_key = :key';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':key', $aryMyValues['adimString']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $newFeedback = $statement -> fetch();
      $statement->closeCursor();

      if($newFeedback != 0) {
        $_SESSION['adim'] = 'cVU7k1hstJ';
      }

      header('Location: ../view/groups.php');
    } else if($action == 'delateGroup') {
      $query = 'SELECT video FROM poeple_group WHERE groups_id = :gID';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':gID', $aryMyValues['groupID']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $newFeedback = $statement -> fetchAll();
      $statement->closeCursor();

      foreach($newFeedback as $curFeedback) {
        if($curFeedback['video'] != null) {
          unlink('../../uploads/'.$curFeedback['video']);
        }
      }
      $query = 'DELETE FROM poeple_group WHERE groups_id = :gID';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':gID', $aryMyValues['groupID']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $statement->closeCursor();

      $query = 'DELETE FROM feedback WHERE groups_id = :gID';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':gID', $aryMyValues['groupID']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $statement->closeCursor();

      $query = 'DELETE FROM groups WHERE groups_id = :gID';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':gID', $aryMyValues['groupID']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $statement->closeCursor();

      header('Location: ../view/groups.php');
    } else if($action == 'delatePost') {
      $query = 'SELECT * FROM poeple_group WHERE ((message = :messagePG) && (posted = :postedPG))';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':postedPG', $aryMyValues['postedPG']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':messagePG', $aryMyValues['messagePG']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $newFeedback = $statement -> fetch();
      $statement->closeCursor();

      if($newFeedback['video'] != null) {
        unlink('../../uploads/'.$newFeedback['video']);
      }

      $query = 'DELETE FROM poeple_group WHERE ((message = :messagePG) && (posted = :postedPG))';
      $statement = $db->prepare($query);
      if (!$statement) {
        exit("Sorry prepare failed");
      }
      $bind_results = $statement->bindValue(':postedPG', $aryMyValues['postedPG']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $bind_results = $statement->bindValue(':messagePG', $aryMyValues['messagePG']);
      if(!$bind_results) {
        exit("Sorry can't bind those value");
      }
      $workQuery = $statement->execute();
      if(!$workQuery) {
        exit("Bad execcution");
      }
      $statement->closeCursor();

      header('Location: ../view/posts.php');
    } else if($action == 'uploadUserImg') {

      if(isset($_FILES['userNewImage']) == true) {
        $file = $_FILES['userNewImage'];
        $allowedExistion = array('jpg', 'png');
        $fileNameArry = explode('.', $file['name']);
        $fileName = $file['name'];
        $curDateTime = date("Y-m-d H:i:s");

        if(($file['size'] <= 10000000) && (in_array(end($fileNameArry), $allowedExistion)) && (strlen($fileName) <= 7) && ($file['error']) == 0) {
          $fileNewName = "{$fileNameArry[0]}{$curDateTime}.{$fileNameArry[1]}";
          $query = 'SELECT * from person
           where ((fname = :fname) && (lname = :lname) && (birthday = :birthday))';
          $statement = $db->prepare($query);
          if (!$statement) {
            exit("Sorry prepare failed");
          }
          $bind_results = $statement->bindValue(':fname', $_SESSION['fName']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':lname', $_SESSION['lName']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':birthday', $_SESSION['bDate']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $workQuery = $statement->execute();
          if(!$workQuery) {
            exit("Bad execcution");
          }
          $newFeedback = $statement -> fetch();
          $statement->closeCursor();
          if($newFeedback['picture'] != null) {
            unlink('../../uploads/'.$newFeedback['picture']);
          }

          $query = 'UPDATE person
            set picture = :file
           where ((fname = :fname) && (lname = :lname) && (birthday = :birthday))';
          $statement = $db->prepare($query);
          if (!$statement) {
            exit("Sorry prepare failed");
          }
          $bind_results = $statement->bindValue(':file', $fileNewName);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':fname', $_SESSION['fName']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':lname', $_SESSION['lName']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $bind_results = $statement->bindValue(':birthday', $_SESSION['bDate']);
          if(!$bind_results) {
            exit("Sorry can't bind those value");
          }
          $workQuery = $statement->execute();
          if(!$workQuery) {
            exit("Bad execcution");
          }
          $statement->closeCursor();

          move_uploaded_file($file['tmp_name'], '../../uploads/'.$fileNewName);
        }
      }
      header('Location: ../view/groups.php');
    }
  }

  main();
