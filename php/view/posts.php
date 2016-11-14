<?php
  function adimDelete($message, $posted) {
    if(isset($_COOKIE['adim']) == true) {
      if($_COOKIE['adim'] == 'cVU7k1hstJ') {
        return "
          <form action=\"../controller/action.php\" method=\"post\">
            <input type=\"hidden\" type=\"text\" name=\"action\" value=\"delatePost\">
            <input type=\"hidden\" type=\"text\" name=\"postedPG\" value=\"{$posted}\">
            <input type=\"hidden\" type=\"text\" name=\"messagePG\" value=\"{$message}\">
            <button type=\"submit\" class=\"btn btn-default\">Delete</button>
          </form>
        ";
      } else {
        return "";
      }
    } else {
      return "";
    }
  }

  function buildPosts() {
    require('../model/db.php');

    $strPosts = "";

    $query = "select *
    from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
    where name = :name
    order by posted";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $_COOKIE['name']);
    $statement->execute();
    $aGroups = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($aGroups as $aGroup) {
      $adimDelete = adimDelete($aGroup['message'], $aGroup['posted']);
      $fulName = $aGroup['fname'].' '.$aGroup['lname'];

      if (($aGroup['posted'] != '') && ($aGroup['message'] != '')) {
        $strPosts = $strPosts."
          <div class=\"panel panel-default\">
            <div class=\"panel-heading\">{$fulName}</div>
            <div class=\"panel-body\">{$aGroup['message']}{$adimDelete}</div>
            <div class=\"panel-footer\">{$aGroup['posted']}</div>
          </div>
        ";
      }
    }

    return $strPosts;
  }

  function findMembers() {
    require('../model/db.php');

    $strPosts = "";

    $query = "select fname, lname
    from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
    where name = :name
    GROUP BY person.person_id
    order by posted";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $_COOKIE['name']);
    $statement->execute();
    $aGroups = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($aGroups as $aGroup) {
      $userPosts = $aGroup['fname'].' '.$aGroup['lname'];
      $strPosts = $strPosts."
        <li><a href=\"#\" style=\"cursor:default\">{$userPosts}</a></li>
      ";
    }

    return $strPosts;
  }

  function makeFormHelper() {
    return "
      <div id=\"addNewPost\" class=\"tab-pane fade\">
        <div class=\"panel panel-default\">
          <div class=\"panel-heading\">Add a new post</div>
          <div class=\"panel-body\">
            <form id=\"postForm\" action=\"../controller/action.php\" method=\"post\">
              <input type=\"hidden\" type=\"text\" name=\"action\" value=\"addPost\">
              <div class=\"form-group\">
                <label for=\"message\">Message</label>
                <textarea class=\"form-control\" rows=\"5\" name=\"message\"></textarea>
              </div>
              <button type=\"submit\" class=\"btn btn-default\" disabled>Submit</button>
            </form>
          </div>
        </div>
      </div>
    ";
  }

  function checkAdim() {
    require('../model/db.php');

    $query = "select *
    from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
    where name = :name
    order by posted
    ";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $_COOKIE['name']);
    $statement->execute();
    $aGroups = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($aGroups as $aGroup) {
      if(($_COOKIE['fName'] != $aGroup['fname']) || ($_COOKIE['lName'] != $aGroup['lname']) || ($_COOKIE['bDate'] != $aGroup['birthday'])) {
        return false;
      }
    }

    return true;
  }

  function makeForm() {
    $txt = "";

    if(isset($_COOKIE['adim']) == true) {
      if($_COOKIE['adim'] == 'cVU7k1hstJ') {
        if(checkAdim() == true) {
          $txt = makeFormHelper();
        } else {
          $txt = "<div id=\"addNewPost\" class=\"tab-pane fade\">
            <div class=\"panel panel-default\">
              <div class=\"panel-heading\">Sorry</div>
              <div class=\"panel-body\">
                <h3>Sorry administrator, you need to add yourself to the group.</h3>
              </div>
            </div>
          </div>";
        }
      } else {
        $txt = makeFormHelper();
      }
    } else {
      $txt = makeFormHelper();
    }

    return $txt;
  }

  function adimDataTab() {
    if(isset($_COOKIE['adim']) == true) {
      if($_COOKIE['adim'] == 'cVU7k1hstJ') {
        return "
          <li role=\"presentation\">
            <a data-toggle=\"tab\" href=\"#adimData\">
              Administrator
            </a>
          </li>
        ";
      } else {
        return "";
      }
    } else {
      return "";
    }
  }

  function adimData() {
    require('../model/db.php');

    $data = "";

    $query = "SELECT DATE(poeple_group.posted) as dbDate, CONCAT(person.fname,' ', person.lname, ' ',person.birthday) as fullPerson
      from  (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
      where ((message IS NULL) && (groups.name = :name))
    ";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $_COOKIE['name']);
    $statement->execute();
    $aGroups = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($aGroups as $aGroup) {
      $dbTime = new DateTime(date('Y-m-d',strtotime($aGroup['dbDate'])));
      $phpTime  = new DateTime(date('Y-m-d', time()));
      $daysDiff = $phpTime->diff($dbTime);
      $howLong = $daysDiff->days;

      $data = $data."
        <ol>
          <li>Name: {$aGroup['fullPerson']}</li>
          <li>Number of days: {$howLong}</li>
        </ol>
      ";
    }

    if(isset($_COOKIE['adim']) == true) {
      if($_COOKIE['adim'] == 'cVU7k1hstJ') {
        return "
          <div id=\"adimData\" class=\"tab-pane fade\">
            <div class=\"panel panel-default\">
              <div class=\"panel-heading\">Administrator data</div>
              <div class=\"panel-body\">
                {$data}
              </div>
            </div>
          </div>
        ";
      } else {
        return "";
      }
    } else {
      return "";
    }
  }

  function master() {
    require_once('head.php');
    require_once('footer.php');

    $head = headData('Posts');
    $footer = footerData();
    $posts = buildPosts();
    $members = findMembers();
    $postForm = makeForm();
    $adimDataTab = adimDataTab();
    $adimData = adimData();

    return "
      {$head}
      <ul class=\"nav nav-tabs\">
        <li role=\"presentation\">
          <a id=\"toHome\" data-toggle=\"tab\" href=\"groups.php\">
            <span class=\"glyphicon glyphicon-home\" aria-hidden=\"true\"></span>
          </a>
        </li>
        <li role=\"presentation\" class=\"active\">
          <a data-toggle=\"tab\" href=\"#groups\">
            Groups
          </a>
        </li>
        <li role=\"presentation\">
          <a data-toggle=\"tab\" href=\"#addNewPost\">
            <span class=\"glyphicon glyphicon-plus-sign\"></span>
          </a>
        </li>
        <li role=\"presentation\" class=\"dropdown\">
          <a data-toggle=\"dropdown\" role=\"buttom\" class=\"dropdown-toggle\" href=\"#\">
            Members<span class=\"caret\"></span>
          </a>
          <ul role=\"menu\" class=\"dropdown-menu\">
            {$members}
          </ul>
        </li>
        {$adimDataTab}
        <li role=\"presentation\" class=\"pull-right\">
          <a data-toggle=\"tab\" href=\"#\">{$_COOKIE['name']}</a>
        </li>
      </ul>

      <div class=\"tab-content\">
        {$postForm}
        <div id=\"groups\" class=\"tab-pane fade in active\">
          {$posts}
        </div>
        {$adimData}
      </div>
      {$footer}
    ";
  }

  echo master();
?>
