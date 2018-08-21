<?hh
  function main() {
    require('defense.php');

    !isset($_SESSION) ? session_start() : null;

    if (($_SERVER['REQUEST_METHOD'] === 'POST') && (checkToken() == true)) {
      $_SESSION['IPAddress'] = $_SERVER['REMOTE_ADDR'];
      $_SESSION['BrowserData'] = $_SERVER['HTTP_USER_AGENT'];
      $_SESSION['loginValid'] = true;
      $_SESSION['loginTime'] = date('Y-m-d H:i:s');
      actions();
    } else {
      session_unset();
     	session_destroy();
      exit('Something happened');
    }
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

      $newFeedback = handleSQL("SELECT * from person where ((fname = '{$fName}') && (lname = '{$lName}') && (birthday = '{$bDate}')) LIMIT 1", [], [], 1); 

      if ($newFeedback == 0) {
        handleSQL("INSERT INTO person (fname, lname, birthday) VALUES ('{$fName}', '{$lName}', '{$bDate}')", [], [], 3);
      }

      $_SESSION['fName'] = $fName;
      $_SESSION['lName'] = $lName;
      $_SESSION['bDate'] = $bDate;

      header('Location: ../view/groups.php');
    } else if ($action == 'addGroup') {
      $newFeedback = handleSQL("select * from groups where name = '{$aryMyValues['Name']}' ",[],[],0);

      if ($newFeedback == 0) {
        handleSQL("INSERT INTO groups (name, date_added) VALUES ('{$aryMyValues['Name']}', :post) ",[':post'],[date('Y-m-d H:i:s')],3);
        $curUser = handleSQL("select * from person where ((fname = '{$_SESSION['fName']}') && (lname = '{$_SESSION['lName']}') && (birthday = '{$_SESSION['bDate']}'))", [], [], 0);
        $curGroup = handleSQL("select * from groups where name = '{$aryMyValues['Name']}'",[],[],0);
        handleSQL("INSERT INTO poeple_group (groups_id, person_id, posted) VALUES ({$curGroup['groups_id']}, {$curUser['person_id']}, :post)",[':post'], [date('Y-m-d H:i:s')], 3);
      }

      header('Location: ../view/groups.php');
    } else if ($action == 'addUsersToGroup') {
      $newFeedback = handleSQL("select * from poeple_group where (groups_id = '{$aryMyValues['selectGroup']}') && (person_id = '{$aryMyValues['selectUser']}')", [],[], 0);

      if ($newFeedback == 0) {
        handleSQL("INSERT INTO poeple_group (groups_id, person_id, posted) values (:group, :person, :post)", [':group',':person', ':post'],[$aryMyValues['selectGroup'], $aryMyValues['selectUser'], date('Y-m-d H:i:s')]);
      }

      header('Location: ../view/groups.php');
    } else if ($action == 'seeWhoPosts') {
      $_SESSION['name'] = $aryMyValues['whoPost'];
      header('Location: ../view/posts.php');
    } else if ($action == 'addPost') {
      $newFeedback = handleSQL("SELECT person.person_id, groups.groups_id from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id where ((fname = '{$_SESSION['fName']}') && (lname = '{$_SESSION['lName']}') && (birthday = '{$_SESSION['bDate']}'))", [], [], 0);

      if(isset($_FILES['userPostVideo']) == true) {
        $file = $_FILES['userPostVideo'];
        $allowedExistion = array('mp4');
        $fileNameArry = explode('.', $file['name']);
        $fileName = $file['name'];
        $curDateTime = date("Y-m-d H:i:s");

        if(($file['size'] <= 20000000) && (end($fileNameArry) == 'mp4') && (strlen($fileName) <= 7) && ($file['error']) == 0) {
          $fileNewName = "{$fileNameArry[0]}{$curDateTime}.{$fileNameArry[1]}";

          handleSQL("INSERT INTO poeple_group (groups_id, person_id, message, posted, video) VALUES ({$newFeedback['groups_id']}, {$newFeedback['person_id']}, '{$aryMyValues['message']}', :posted, '{$fileNewName}')", [':posted'], [date('Y-m-d H:i:s')], 3);
         
          move_uploaded_file($file['tmp_name'], '../../uploads/'.$fileNewName);
        } else {
          handleSQL("INSERT INTO poeple_group (groups_id, person_id, message, posted) VALUES ({$newFeedback['groups_id']}, {$newFeedback['person_id']}, '{$aryMyValues['message']}', :posted)", [":posted"], [date('Y-m-d H:i:s')]);
        }
      } else {
        handleSQL("INSERT INTO poeple_group (groups_id, person_id, message, posted) VALUES ({$newFeedback['groups_id']}, {$newFeedback['person_id']}, '{$aryMyValues['message']}', :posted)", [':posted'], [date('Y-m-d H:i:s')], 3);
      }

      header('Location: ../view/posts.php');
    } else if ($action == 'logOut') {
      session_unset();
     	session_destroy();

      header('Location: ../../index.php');
    } else if($action == 'giveFeedback') {
      $newFeedback = handleSQL("SELECT * from person where ((fname = '{$_SESSION['fName']}') && (lname = '{$_SESSION['lName']}') && (birthday = '{$_SESSION['bDate']}')) LIMIT 1", [], [], 0);

      if($aryMyValues['selectGroupFeedback'] == -1) {
        handleSQL("INSERT INTO feedback (message, rating, person_id, placed) VALUES ('{$aryMyValues['txtMessage']}', {$aryMyValues['rating']}, {$newFeedback['person_id']}, :placed)",[':placed'], [date('Y-m-d H:i:s')], 3);
      } else {
        handleSQL("INSERT INTO feedback (message, rating, person_id, groups_id, placed) VALUES ('{$aryMyValues['txtMessage']}', {$aryMyValues['rating']}, {$newFeedback['person_id']}, {$aryMyValues['selectGroupFeedback']}, :placed)",[':placed'],[date('Y-m-d H:i:s')],3);
      }

      header('Location: ../view/groups.php');
    } else if($action == 'adim') {
      $newFeedback = handleSQL("SELECT * from adims where adim_key = '{$aryMyValues['adimString']}' ",[], [],0);

      if($newFeedback != 0) {
        $_SESSION['adim'] = 'cVU7k1hstJ';
      }

      header('Location: ../view/groups.php');
    } else if($action == 'delateGroup') {
      $newFeedback = handleSQL("SELECT video FROM poeple_group WHERE groups_id = {$aryMyValues['groupID']}",[],[],1);

      foreach($newFeedback as $curFeedback) {
        if($curFeedback['video'] != null) {
          unlink('../../uploads/'.$curFeedback['video']);
        }
      }

      handleSQL("DELETE FROM poeple_group WHERE groups_id = {$aryMyValues['groupID']}", [], [], 3);
      handleSQL("DELETE FROM feedback WHERE groups_id = {$aryMyValues['groupID']}", [], [], 3);
      handleSQL("DELETE FROM groups WHERE groups_id = {$aryMyValues['groupID']}", [], [], 3);

      header('Location: ../view/groups.php');
    } else if($action == 'delatePost') {
      $newFeedback = handleSQL("SELECT * FROM poeple_group WHERE ((message = '{$aryMyValues['messagePG']}') && (posted = :postedPG))", [':postedPG'], [$aryMyValues['postedPG']]);

      if($newFeedback['video'] != null) {
        unlink('../../uploads/'.$newFeedback['video']);
      }
      handleSQL("DELETE FROM poeple_group WHERE ((message = '{$aryMyValues['messagePG']}') && (posted = :postedPG))", [':postedPG'], [$aryMyValues['postedPG']]);

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
          $newFeedback = handleSQL("SELECT * from person where ((fname = '{$_SESSION['fName']}') && (lname = '{$_SESSION['lName']}') && (birthday = '{$_SESSION['bDate']}')) LIMIT 1", [], [], 0);
          if($newFeedback['picture'] != null) {
            unlink('../../uploads/'.$newFeedback['picture']);
          }

          handleSQL("UPDATE person set picture = '{$fileNewName}' where ((fname = '{$_SESSION['fName']}') && (lname = '{$_SESSION['lName']}') && (birthday = '{$_SESSION['bDate']}'))",[],[],3);

          move_uploaded_file($file['tmp_name'], '../../uploads/'.$fileNewName);
        }
      }
      header('Location: ../view/groups.php');
    }
  }

  main();
