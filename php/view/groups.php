<?php
  require_once('head.php');
  require_once('footer.php');
  require_once('adim.php');
  require_once('about.php');
  require_once('feedback.php');
  require_once('groupsContent.php');
  require_once('buildJson.php');

  function mainPage() {
    builder();
    $head = headData("Groups");
    $footer = footerData();
    $about = aboutData();
    $adim = adimData();
    $feedback = feedbackData();
    $groups = groupData();
    $groupForm = groupForm();
    $groupUserForm = groupUserForm();
    $User = $_COOKIE['fName'].' '.$_COOKIE['lName'];

    return "
      {$head}
      <ul id=\"tabs\" class=\"nav nav-tabs\">
        <li role=\"presentation\" class=\"active\">
          <a data-toggle=\"tab\" href=\"#groupsMenu\">Groups</a>
        </li>
        <li role=\"presentation\">
          <a data-toggle=\"tab\" href=\"#aboutMenu\">About</a>
        </li>
        <li role=\"presentation\">
          <a data-toggle=\"tab\" href=\"#feedbackMenu\">Feedback</a>
        </li>
        <li role=\"presentation\">
          <a data-toggle=\"tab\" href=\"#adimMenu\">Administrator</a>
        </li>
        <li role=\"presentation\" class=\"pull-right\">
          <a data-toggle=\"tab\" href=\"#\">{$User}</a>
        </li>
      </ul>

      <div class=\"container\">
        <div class=\"tab-content\">
          {$groups}
          {$about}
          {$feedback}
          {$adim}
        </div>
      </div>

    <div class=\"container\">
      <div class=\"well\">
        <form action=\"../controller/action.php\" method=\"post\">
          <input type=\"hidden\" type=\"text\" name=\"action\" value=\"logOut\">
          <button type=\"submit\" class=\"btn btn-default\"><span class=\"glyphicon glyphicon-log-out\"></span></button>
        </form>
        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"modal\" data-target=\"#addUsers\">
          <span class=\"glyphicon glyphicon-user\"></span>
        </button>
        <button type=\"button\" class=\"btn btn-default\" data-toggle=\"modal\" data-target=\"#myModal\">
          <span class=\"glyphicon glyphicon-plus-sign\"></span>
        </button>
      </div>
    </div>

    <div class=\"modal fade\" id=\"addUsers\" role=\"dialog\">
      <div class=\"modal-dialog\">
        <div class=\"modal-content\">
          <div class=\"modal-header\">
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
            <h2 class=\"modal-title\">Add user to group</h2>
          </div>
          <div class=\"modal-body\">
            {$groupUserForm}
          </div>
        </div>
      </div>
    </div>
  </div>

    <div class=\"modal fade\" id=\"myModal\" role=\"dialog\">
      <div class=\"modal-dialog\">
        <div class=\"modal-content\">
          <div class=\"modal-header\">
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
            <h2 class=\"modal-title\">Add new group</h2>
          </div>
          <div class=\"modal-body\">
            {$groupForm}
          </div>
        </div>
      </div>
    </div>
  </div>
      {$footer}
    ";
  }

  echo mainPage();
?>
