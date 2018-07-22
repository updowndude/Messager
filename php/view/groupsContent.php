<?php
  function groupsDisplay() {
    require('../model/db.php');
    // require('../controller/defense.php');
    $token = makeToken();
    $data = "";
    $cnt = 2;

    if(isset($_SESSION['adim']) == true) {
      if($_SESSION['adim'] == 'cVU7k1hstJ') {
        $query = "select * from groups";
        $statement = $db->prepare($query);
        if (!$statement) {
          exit("Sorry prepare failed");
        }
        $workQuery = $statement->execute();
        if(!$workQuery) {
          exit("Bad execcution");
        }
        $newFeedback = $statement -> fetchAll();
        $statement->closeCursor();

        foreach($newFeedback as $curFeedback) {
          $curName = $curFeedback['name'];

          if($cnt % 2 == 0) {
            $row = "info";
          } else {
            $row = "danger";
          }

          $data = $data."
            <div class=\"well personGroups {$row}\">
              <form action=\"../controller/action.php\" method=\"post\">
                <input type=\"hidden\" type=\"text\" name=\"action\" value=\"seeWhoPosts\">
                <input class=\"searchMe\" type=\"hidden\" type=\"text\" value=\"{$curName}\" name=\"whoPost\">
                {$token}
                <button type=\"submit\" class=\"btn btn-{$row}\"><strong>{$curName}</strong></button>
              </form>
              <form action=\"../controller/action.php\" method=\"post\">
                <input type=\"hidden\" type=\"text\" name=\"action\" value=\"delateGroup\">
                <input type=\"hidden\" type=\"text\" name=\"groupID\" value=\"{$curFeedback['groups_id']}\">
                {$token}
                <button type=\"submit\" class=\"btn btn-{$row}\">Delete</button>
              </form>
            </div>
          ";
          $cnt++;
        }
      } else {
        $data = groupDisplayHelper();
      }
    } else {
      $data = groupDisplayHelper();
    }
    return $data;
  }

  function groupDisplayHelper() {
    require('../model/db.php');
    // require('../controller/defense.php');
    $token = makeToken();
    $strText = "";
    $cnt = 2;

    $query = "select *
      from (person inner join poeple_group on person.person_id = poeple_group.person_id)
        join groups on groups.groups_id = poeple_group.groups_id
      where ((fname = :fName) && (lname = :lName) && (birthday = :bDate))";
     // GROUP BY poeple_group.groups_id";
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
    $newFeedback = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($newFeedback as $curFeedback) {
      $curName = $curFeedback['name'];

      if($cnt % 2 == 0) {
        $row = "info";
      } else {
        $row = "danger";
      }

      $strText = $strText."
        <div class=\"well personGroups {$row}\">
          <form action=\"../controller/action.php\" method=\"post\">
            <input type=\"hidden\" type=\"text\" name=\"action\" value=\"seeWhoPosts\">
            <input class=\"searchMe\" type=\"hidden\" type=\"text\" value=\"{$curName}\" name=\"whoPost\">
            {$token}
            <button type=\"submit\" class=\"btn btn-{$row}\"><strong>{$curName}</strong></button>
          </form>
        </div>
      ";
      $cnt++;
    }

    return $strText;
  }

  function groupData() {
    $groupData = groupsDisplay();

    return "
      <div id=\"groupsMenu\" class=\"tab-pane fade in active\">
        {$groupData}
      </div>
    ";
  }

  function groupUsersFromGroup() {
    require('../model/db.php');

    $toGroups = "";

    $query = "select *
      from (person inner join poeple_group on person.person_id = poeple_group.person_id)
        join groups on groups.groups_id = poeple_group.groups_id
      where ((fname = :fName) && (lname = :lName) && (birthday = :bDate))";
      // GROUP BY poeple_group.groups_id";
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
    $newFeedback = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($newFeedback as $curFeedback) {
      $toGroups = $toGroups."
        <option value=\"{$curFeedback['groups_id']}\">{$curFeedback['name']}</option>
      ";
    }

    return $toGroups;
  }

  function groupUsersFromUser() {
    require('../model/db.php');

    $users = "";

    $query = "select * from person";
    $statement = $db->prepare($query);
    if (!$statement) {
      exit("Sorry prepare failed");
    }
    $workQuery = $statement->execute();
    if(!$workQuery) {
      exit("Bad execcution");
    }
    $newFeedback = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($newFeedback as $curFeedback) {
      if (($curFeedback['fname'] !=  $_SESSION['fName']) || ($curFeedback['lname'] !=  $_SESSION['lName']) || ($curFeedback['birthday'] !=  $_SESSION['bDate'])) {
        $curFullName = $curFeedback['fname'].' '.$curFeedback['lname'];

        $users = $users."
          <option value=\"{$curFeedback['person_id']}\">{$curFullName}</option>
        ";
      }
    }
  }

  function groupUserForm() {
    require('../model/db.php');
    // require('../controller/defense.php');
    $token = makeToken();
    $users = "";
    $toGroups = "";

    if(isset($_SESSION['adim']) == true) {
      if($_SESSION['adim'] == 'cVU7k1hstJ') {
        $query = "select * from groups";
        $statement = $db->prepare($query);
        $statement->execute();
        $newFeedback = $statement -> fetchAll();
        $statement->closeCursor();

        foreach($newFeedback as $curFeedback) {
          $curName = $curFeedback['name'];

          $toGroups = $toGroups."
            <option value=\"{$curFeedback['groups_id']}\">{$curFeedback['name']}</option>
          ";
        }

        $query = "select * from person";
        $statement = $db->prepare($query);
        $statement->execute();
        $newFeedback = $statement -> fetchAll();
        $statement->closeCursor();

        foreach($newFeedback as $curFeedback) {
          $curFullName = $curFeedback['fname'].' '.$curFeedback['lname'];

          $users = $users."
            <option value=\"{$curFeedback['person_id']}\">{$curFullName}</option>
          ";
        }
      } else {
        $toGroups = groupUsersFromGroup();
        $users = groupUsersFromUser();
      }
    } else {
      $toGroups = groupUsersFromGroup();
      $users = groupUsersFromUser();
    }

    return "
      <form id=\"groupUserForm\" action=\"../controller/action.php\" method=\"post\">
        <input type=\"hidden\" type=\"text\" name=\"action\" value=\"addUsersToGroup\">
        <div class=\"form-group\">
          <label for=\"Name\">Add user</label>
          <select class=\"form-control\" name=\"selectUser\">
            {$users}
          </select>
        </div>
        <div class=\"form-group\">
          <label for=\"Name\">To group</label>
          <select class=\"form-control\" name=\"selectGroup\">
            {$toGroups}
          </select>
        </div>
        {$token}
        <button type=\"submit\" class=\"btn btn-info\">Add</button>
      </form>
    ";
  }

  function groupForm() {
    // require('../controller/defense.php');
    $token = makeToken();

    return "
      <form id=\"groupForm\" action=\"../controller/action.php\" method=\"post\">
        <input type=\"hidden\" type=\"text\" name=\"action\" value=\"addGroup\">
        <div class=\"form-group\">
          <label for=\"Name\">Name</label>
          <input type=\"text\" class=\"form-control\" name=\"Name\">
        </div>
        {$token}
        <button type=\"submit\" class=\"btn btn-info\" disabled>Add</button>
      </form>
    ";
  }
?>
