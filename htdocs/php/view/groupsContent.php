<?hh
  function groupsDisplay() {
    require('php/model/db.php');
    // require('php/controller/defense.php');
    $token = makeToken();
    $data = [];
    $cnt = 2;

    if(isset($_SESSION['adim']) == true) {
      if($_SESSION['adim'] == 'cVU7k1hstJ') {
        $newFeedback = handleSQL("select * from groups", [],[], 1);

        foreach($newFeedback as $curFeedback) {
          $curName = $curFeedback['name'];

          if($cnt % 2 == 0) {
            $row = "info";
          } else {
            $row = "danger";
          }
          array_push($data,(<div class="well personGroups {$row}">
              <form action="php/controller/action.php" method="post">
                <input type="hidden" name="action" value="seeWhoPosts"/>
                <input class="searchMe" type="hidden" value={$curName} name="whoPost"/>
                <input type="hidden" name="token" value={$token} />
                <button type="submit" class="btn btn-{$row}"><strong>{$curName}</strong></button>
              </form>
              <form action="php/controller/action.php" method="post">
                <input type="hidden" name="action" value="delateGroup"/>
                <input type="hidden" name="groupID" value={$curFeedback['groups_id']}/>
                <input type="hidden" name="token" value={$token} />
                <button type="submit" class="btn btn-{$row}">Delete</button>
              </form>
            </div>));
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
    require('php/model/db.php');
    // require('php/controller/defense.php');
    $token = makeToken();
    $strText = [];
    $cnt = 2;

    $newFeedback = handleSQL("select *
      from (person inner join poeple_group on person.person_id = poeple_group.person_id)
        join groups on groups.groups_id = poeple_group.groups_id
      where ((fname = '{$_SESSION['fName']}') && (lname = '{$_SESSION['lName']}') && (birthday = '{$_SESSION['bDate']}'))", [],[],1);

    foreach($newFeedback as $curFeedback) {
      $curName = $curFeedback['name'];

      if($cnt % 2 == 0) {
        $row = "info";
      } else {
        $row = "danger";
      }

      array_push($strText, (<div class="well personGroups {$row}">
          <form action="php/controller/action.php" method="post">
            <input type="hidden" name="action" value="seeWhoPosts"/>
            <input class="searchMe" type="hidden" value={$curName} name="whoPost"/>
            <input type="hidden" name="token" value={$token} />
            <button type="submit" class="btn btn-{$row}"><strong>{$curName}</strong></button>
          </form>
        </div>));
      $cnt++;
    }

    return $strText;
  }

  function groupData() {
    $groupData = groupsDisplay();

    return (
      <div id="groupsMenu" class="tab-pane fade in active">
        {$groupData}
      </div>
    );
  }

  function groupUsersFromGroup() {
    require('php/model/db.php');

    $toGroups = [];

    $newFeedback = handleSQL("select *
      from (person inner join poeple_group on person.person_id = poeple_group.person_id)
        join groups on groups.groups_id = poeple_group.groups_id
      where ((fname = '{$_SESSION['fName']}') && (lname = '{$_SESSION['lName']}') && (birthday = '{$_SESSION['bDate']}'))", [],[], 1);

    foreach($newFeedback as $curFeedback) {
      array_push($toGroups, <option value={$curFeedback['groups_id']}>{$curFeedback['name']}</option>);
    }

    return $toGroups;
  }

  function groupUsersFromUser() {
    require('php/model/db.php');

    $users = [];

    $newFeedback = handleSQL("select * from person", [],[], 1);

    foreach($newFeedback as $curFeedback) {
      if (($curFeedback['fname'] !=  $_SESSION['fName']) || ($curFeedback['lname'] !=  $_SESSION['lName']) || ($curFeedback['birthday'] !=  $_SESSION['bDate'])) {
        $curFullName = $curFeedback['fname'].' '.$curFeedback['lname'];
        array_push($curFullName, <option value={$curFeedback['person_id']}>{$curFullName}</option>);
      }
    }
  }

  function groupUserForm() {
    require('php/model/db.php');
    // require('php/controller/defense.php');
    $token = makeToken();
    $users = [];
    $toGroups = [];

    if(isset($_SESSION['adim']) == true) {
      if($_SESSION['adim'] == 'cVU7k1hstJ') {
        $newFeedback = handleSQL("select * from groups", [],[], 1);
        foreach($newFeedback as $curFeedback) {
          $curName = $curFeedback['name'];
          array_push($toGroups, <option value={$curFeedback['groups_id']}>{$curFeedback['name']}</option>);
        }

        $newFeedback = handleSQL("select * from person", [],[], 1);

        foreach($newFeedback as $curFeedback) {
          $curFullName = $curFeedback['fname'].' '.$curFeedback['lname'];
          array_push($users,  <option value={$curFeedback['person_id']}>{$curFullName}</option>);
        }
      } else {
        $toGroups = groupUsersFromGroup();
        $users = groupUsersFromUser();
      }
    } else {
      $toGroups = groupUsersFromGroup();
      $users = groupUsersFromUser();
    }

    return (
      <form id="groupUserForm" action="php/controller/action.php" method="post">
        <input type="hidden" name="action" value="addUsersToGroup"/>
        <div class="form-group">
          <label for="Name">Add user</label>
          <select class="form-control" name="selectUser">
            {$users}
          </select>
        </div>
        <div class="form-group">
          <label for="Name">To group</label>
          <select class="form-control" name="selectGroup">
            {$toGroups}
          </select>
        </div>
        <input type="hidden" name="token" value={$token} />
        <button type="submit" class="btn btn-info">Add</button>
      </form>
    );
  }

  function groupForm() {
    // require('php/controller/defense.php');
    $token = makeToken();

    return (
      <form id="groupForm" action="php/controller/action.php" method="post">
        <input type="hidden" name="action" value="addGroup"/>
        <div class="form-group">
          <label for="Name">Name</label>
          <input type="text" class="form-control" name="Name"/>
        </div>
        <input type="hidden" name="token" value={$token} />
        <button type="submit" class="btn btn-info">Add</button>
      </form>
    );
  }
