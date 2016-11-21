<?php
  function groupsDisplay() {
    require('../model/db.php');

    $data = "";

    if(isset($_COOKIE['adim']) == true) {
      if($_COOKIE['adim'] == 'cVU7k1hstJ') {
        $query = "select * from groups";
        $statement = $db->prepare($query);
        $statement->execute();
        $newFeedback = $statement -> fetchAll();
        $statement->closeCursor();

        foreach($newFeedback as $curFeedback) {
          $curName = $curFeedback['name'];

          $data = $data."
            <div class=\"well personGroups\">
              <form action=\"../controller/action.php\" method=\"post\">
                <input type=\"hidden\" type=\"text\" name=\"action\" value=\"seeWhoPosts\">
                <input class=\"searchMe\" type=\"hidden\" type=\"text\" value=\"{$curName}\" name=\"whoPost\">
                <button type=\"submit\" class=\"btn btn-default\"><strong>{$curName}</strong></button>
              </form>
              <form action=\"../controller/action.php\" method=\"post\">
                <input type=\"hidden\" type=\"text\" name=\"action\" value=\"delateGroup\">
                <input type=\"hidden\" type=\"text\" name=\"groupID\" value=\"{$curFeedback['groups_id']}\">
                <button type=\"submit\" class=\"btn btn-default\">Delete</button>
              </form>
            </div>
          ";
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

    $strText = "";

    $query = "select *
      from (person inner join poeple_group on person.person_id = poeple_group.person_id)
        join groups on groups.groups_id = poeple_group.groups_id
      where ((fname = :fName) && (lname = :lName) && (birthday = :bDate))
      GROUP BY poeple_group.groups_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':fName', $_COOKIE['fName']);
    $statement->bindValue(':lName', $_COOKIE['lName']);
    $statement->bindValue(':bDate', $_COOKIE['bDate']);
    $statement->execute();
    $newFeedback = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($newFeedback as $curFeedback) {
      $curName = $curFeedback['name'];

      $strText = $strText."
        <div class=\"well personGroups\">
          <form action=\"../controller/action.php\" method=\"post\">
            <input type=\"hidden\" type=\"text\" name=\"action\" value=\"seeWhoPosts\">
            <input class=\"searchMe\" type=\"hidden\" type=\"text\" value=\"{$curName}\" name=\"whoPost\">
            <button type=\"submit\" class=\"btn btn-default\"><strong>{$curName}</strong></button>
          </form>
        </div>
      ";
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
      where ((fname = :fName) && (lname = :lName) && (birthday = :bDate))
      GROUP BY poeple_group.groups_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':fName', $_COOKIE['fName']);
    $statement->bindValue(':lName', $_COOKIE['lName']);
    $statement->bindValue(':bDate', $_COOKIE['bDate']);
    $statement->execute();
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
    $statement->execute();
    $newFeedback = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($newFeedback as $curFeedback) {
      if (($curFeedback['fname'] !=  $_COOKIE['fName']) || ($curFeedback['lname'] !=  $_COOKIE['lName']) || ($curFeedback['birthday'] !=  $_COOKIE['bDate'])) {
        $curFullName = $curFeedback['fname'].' '.$curFeedback['lname'];

        $users = $users."
          <option value=\"{$curFeedback['person_id']}\">{$curFullName}</option>
        ";
      }
    }
  }

  function groupUserForm() {
    require('../model/db.php');

    $users = "";
    $toGroups = "";

    if(isset($_COOKIE['adim']) == true) {
      if($_COOKIE['adim'] == 'cVU7k1hstJ') {
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
        <button type=\"submit\" class=\"btn btn-default\">Add</button>
      </form>
    ";
  }

  function groupForm() {
    return "
      <form id=\"groupForm\" action=\"../controller/action.php\" method=\"post\">
        <input type=\"hidden\" type=\"text\" name=\"action\" value=\"addGroup\">
        <div class=\"form-group\">
          <label for=\"Name\">Name</label>
          <input type=\"text\" class=\"form-control\" name=\"Name\">
        </div>
        <button type=\"submit\" class=\"btn btn-default\" disabled>Add</button>
      </form>
    ";
  }
?>
