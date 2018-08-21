<?hh
  require('../../vendor/hh_autoload.php');
  require_once('head.php');
  require_once('footer.php');
  require_once('adim.php');
  require_once('about.php');
  require_once('feedback.php');
  require_once('groupsContent.php');
  require_once('buildJson.php');
  require_once('carousel.php');
  require('user.php');

  function mainPage() {
    require('../controller/defense.php');
    builder();
    $token = makeToken();
    $head = headData("Groups");
    $footer = footerData();
    $about = aboutData();
    $adim = adimData();
    $feedback = feedbackData();
    $groups = groupData();
    $groupForm = groupForm();
    $groupUserForm = groupUserForm();
    $curUser = userPanel();
    $curUserImage = userImageSrc();
    $carousel = buildCarousel();
    $User = $_SESSION['fName'].' '.$_SESSION['lName'];
    $expireTime = $_SESSION['loginTime'] + 18000;    

    if(($_SESSION['IPAddress'] == $_SERVER['REMOTE_ADDR']) && ($expireTime > date("Y-m-d H:i:s")) && ($_SESSION['BrowserData'] == $_SERVER['HTTP_USER_AGENT'])) {
      return (  
        <html lang="en">
          {$head}
          <body id="myBody">
              <main>
                <ul id="tabs" class="nav nav-tabs">
                    <li role="presentation" class="active">
                      <a data-toggle="tab" href="#groupsMenu">
                      <u>Groups</u>
                      </a>
                    </li>
                    <li role="presentation">
                      <a data-toggle="tab" href="#aboutMenu">
                      <u>About</u>
                      </a>
                    </li>
                    <li role="presentation">
                      <a data-toggle="tab" href="#feedbackMenu">
                      <u>Feedback</u>
                      </a>
                    </li>
                    <li role="presentation">
                      <a data-toggle="tab" href="#adimMenu">
                      <abbr title="Administrator">Admin</abbr>
                      </a>
                    </li>
                    <li role="presentation">
                      <a data-toggle="tab" href="#Carousel">
                      <u>Carousel</u>
                      </a>
                    </li>
                    <li role="presentation" class="pull-right">
                      <a data-toggle="tab" href="#userMenu">
                      <img src={$curUserImage} alt="user" class="curUserImage"/> <u>{$User}</u>
                      </a>
                    </li>
                </ul>
                <div class="container">
                    <div class="tab-content">
                      {$groups}
                      {$about}
                      {$feedback}
                      {$adim}
                      {$curUser}
                      {$carousel}
                    </div>
                </div>
                <div class="container">
                    <div class="well">
                      <div class="row">
                          <div class="col-xs-2 col-sm-1">
                            <form action="../controller/action.php" method="post">
                                <input type="hidden" name="action" value="logOut"/>
                                <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-log-out"></span></button>
                                <input type="hidden" name="token" value={$token} />
                            </form>
                          </div>
                          <div class="col-xs-2 col-sm-1">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addUsers">
                            <span class="glyphicon glyphicon-user"></span>
                            </button>
                          </div>
                          <div class="col-xs-2 col-sm-1">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
                            <span class="glyphicon glyphicon-plus-sign"></span>
                            </button>
                          </div>
                      </div>
                    </div>
                </div>
                <div class="modal fade" id="addUsers" role="dialog">
                    <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h2 class="modal-title">Add user to group</h2>
                          </div>
                          <div class="modal-body">
                            {$groupUserForm}
                          </div>
                      </div>
                    </div>
                </div>
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h2 class="modal-title">Add new group</h2>
                          </div>
                          <div class="modal-body">
                            {$groupForm}
                          </div>
                      </div>
                    </div>
                </div>
                {$footer}
              </main>
          </body>
        </html>
      );
    } else {
      return "Sorry something when wrong";
    }
  }

  echo mainPage();
