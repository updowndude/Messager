<?hh
  require('../../vendor/hh_autoload.php');

  !isset($_SESSION) ? session_start() : null;

  function makeToken2() {
    if (checkTime2() == false) {
      $token = md5(uniqid(rand(), true));
      $_SESSION['token'] = $token;
      $_SESSION['tokenTime'] = time();
      return (<input type="hidden" name="token" value={$token}/>);
    } else {
      return (<input type="hidden" name="token" value={$_SESSION['token']}/>);
    }
  }

  function checkTime2() {
    if(isset($_SESSION['tokenTime']) == false) {
      return false;
    } else {
      $timer = $_SESSION['tokenTime'] + 60;
    }

    if ($timer < time()) {
      return false;
    } else {
      return true;
    }
  }

  function adimDelete($message, $posted, $row) {
    // require('../controller/defense.php');

    if(isset($_SESSION['adim']) == true) {
      if($_SESSION['adim'] == 'cVU7k1hstJ') {
        $token = makeToken2();

        return (<form action="../controller/action.php" method="post">
            <input type="hidden" name="action" value="delatePost"/>
            <input type="hidden" name="postedPG" value={$posted}/>
            <input type="hidden" name="messagePG" value={$message}/>
            <input type="hidden" name="token" value={$token} />
            <button type="submit" class="btn btn-{$row}">Delete</button>
          </form>);
      } else {
        return <div></div>;
      }
    } else {
      return <div></div>;
    }
  }

  function buildPosts() {
    require('../model/db.php');
    require('user.php');

    $strPosts = [];
    $cnt = 2;
    $curUserImage = userImageSrc();

    $aGroups = handleSQL("select *
    from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
    where name = '{$_SESSION['name']}'
    order by posted", [], [], 1);

    foreach($aGroups as $aGroup) {
      if($cnt % 2 == 0) {
        $row = "info";
      } else {
        $row = "danger";
      }

      $adimDelete = adimDelete($aGroup['message'], $aGroup['posted'], $row);
      $fulName = $aGroup['fname'].' '.$aGroup['lname'];
      $source = "../../uploads/";
      $rowHelper = "panel panel-{$row}";

      if (($aGroup['posted'] != '') && ($aGroup['message'] != '')) {
        if($aGroup['video'] != null) {
          $source = $source.$aGroup['video'];
          $video = (
            <div class="embed-responsive embed-responsive-16by9">
              <video autoplay="" loop="" class="embed-responsive-item" controls="">
                <source src={$source} type="video/mp4"/>
              </video>
            </div>
          ); 
        } else {
          $video = <div></div>;
        }
        array_push($strPosts, (
          <div class={$rowHelper}>
            <div class="panel-heading">
              <img src={$curUserImage} alt="user" class="curUserImage"/>
                <p class="text-warning">{$fulName}</p>
            </div>
            <div class="panel-body">
              <p>{$aGroup['message']}</p>
              {$video}
              {$adimDelete}
            </div>
            <div class="panel-footer">{$aGroup['posted']}</div>
          </div>
        ));

        $cnt++;
      }
    }

    return $strPosts;
  }

  function findMembers() {
    require('../model/db.php');

    $strPosts = [];

    $aGroups = handleSQL("select fname, lname
    from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
    where name = '{$_SESSION['name']}'", [],[], 1);

    foreach($aGroups as $aGroup) {
      $userPosts = $aGroup['fname'].' '.$aGroup['lname'];
      array_push($strPosts, (<li><a href="#" style="cursor:info">{$userPosts}</a></li>));
    }

    return $strPosts;
  }

  function makeFormHelper() {
    require('../controller/defense.php');
    $token = makeToken();

    return (<div id="addNewPost" class="tab-pane fade">
        <div class="panel panel-info">
          <div class="panel-heading">
            <p class="text-warning">Add a new post</p>
          </div>
          <div class="panel-body">
            <form id="postForm" action="../controller/action.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="action" value="addPost"/>
              <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" name="message"></textarea>
              </div>
              <div class="form-group">
                <label>Upload Video</label>
                <input id="btn-file-inner" class="btn btn-info" name="userPostVideo" type="file"/>
              </div>
              <input type="hidden" name="token" value={$token} />
              <button type="submit" class="btn btn-info">Submit</button>
            </form>
          </div>
        </div>
      </div>);
  }

  function checkAdim() {
    require('../model/db.php');

    $aGroups = handleSQL("select *
    from (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
    where name = '{$_SESSION['name']}'
    order by posted", [], [], 1);

    foreach($aGroups as $aGroup) {
      if(($_SESSION['fName'] != $aGroup['fname']) || ($_SESSION['lName'] != $aGroup['lname']) || ($_SESSION['bDate'] != $aGroup['birthday'])) {
        return false;
      }
    }

    return true;
  }

  function makeForm() {
    $txt = "";

    if(isset($_SESSION['adim']) == true) {
      if($_SESSION['adim'] == 'cVU7k1hstJ') {
        if(checkAdim() == true) {
          $txt = makeFormHelper();
        } else {
          $txt = (<div id="addNewPost" class="tab-pane fade">
            <div class="panel panel-info">
              <div class="panel-heading">
                <p class="text-warning">Sorry</p>
              </div>
              <div class="panel-body">
                <h3>Sorry <abbr title="Administrator">Admin</abbr>, you need to add yourself to the group.</h3>
              </div>
            </div>
          </div>);
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
    if(isset($_SESSION['adim']) == true) {
      if($_SESSION['adim'] == 'cVU7k1hstJ') {
        return (<li role="presentation">
            <a data-toggle="tab" href="#adimData">
              <abbr title="Administrator">Admin</abbr>
            </a>
          </li>);
      } else {
        return <li></li>;
      }
    } else {
      return <li></li>;
    }
  }

  function adimDataName() {
    require('../model/db.php');

    $data = [];
    $cnt = 2;

    $aGroups = handleSQL("SELECT DATE(poeple_group.posted) as dbDate, CONCAT(person.fname,' ', person.lname) as fullPerson, person.birthday
      from  (person inner join poeple_group on person.person_id = poeple_group.person_id) join groups on groups.groups_id = poeple_group.groups_id
      where ((message IS NULL) && (groups.name = '{$_SESSION['name']}'))",[],[],1);

    foreach($aGroups as $aGroup) {
      $dbTime = new DateTime(date('Y-m-d',strtotime($aGroup['dbDate'])));
      $phpTime  = new DateTime(date('Y-m-d', time()));
      $daysDiff = $phpTime->diff($dbTime);
      $howLong = $daysDiff->days;

      if($cnt % 2 == 0) {
        $row = "info";
      } else {
        $row = "danger";
      }

      array_push($data, (<tr class={$row}>
          <td><mark>{$aGroup['fullPerson']}</mark></td>
          <td>{$aGroup['birthday']}</td>
          <td>{$howLong}</td>
        </tr>));
      $cnt++;
    }

    if(isset($_SESSION['adim']) == true) {
      if($_SESSION['adim'] == 'cVU7k1hstJ') {
        return (<div id="adimData" class="tab-pane fade">
            <div class="panel panel-info">
              <div class="panel-heading">
                <p class="text-warning"><abbr title="Administrator">Admin</abbr> data</p>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                 <table class="table table-hover">
                   <thead>
                     <tr>
                       <th>Name</th>
                       <th>Brithday</th>
                       <th>Number of days:</th>
                     </tr>
                   </thead>
                   <tbody>
                    {$data}
                   </tbody>
                 </table>
               </div>
              </div>
            </div>
          </div>);
      } else {
        return <div></div>;
      }
    } else {
      return <div></div>;
    }
  }

  function groupInfo() {
    return (<div id="groupInfo" class="tab-pane fade">
        <div class="panel panel-info">
          <div class="panel-heading">
            <p class="text-warning">Group data</p>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
             <table class="table table-hover">
               <thead>
                 <tr>
                   <th>Name of group</th>
                 </tr>
               </thead>
               <tbody>
                 <tr class="info">
                   <td><mark>{$_SESSION['name']}</mark></td>
                 </tr>
               </tbody>
             </table>
           </div>
          </div>
        </div>
      </div>);
  }

  function master() {
    require_once('head.php');
    require_once('footer.php');

    $head = headData('Posts');
    $footer = footerData();
    $postForm = makeForm();
    $adimDataTab = adimDataTab();
    $adimData = adimDataName();
    $groupInfo = groupInfo();
    $expireTime = $_SESSION['loginTime'] + 18000;
    $posts = buildPosts();
    $members = findMembers();

    $page = (
      <html lang="en">
        {$head}
        <body id="myBody">
            <main>
              <ul id="tabs" class="nav nav-tabs">
                  <li role="presentation">
                    <a id="toHome" href="groups.php">
                      <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                    </a>
                  </li>
                  <li role="presentation" class="active">
                    <a data-toggle="tab" href="#groups">
                    <u>Groups</u>
                    </a>
                  </li>
                  <li role="presentation">
                    <a data-toggle="tab" href="#addNewPost">
                    <span class="glyphicon glyphicon-plus-sign"></span>
                    </a>
                  </li>
                  <li role="presentation" class="dropdown">
                    <a data-toggle="dropdown" role="buttom" class="dropdown-toggle" href="#">
                    <u>Members</u>
                    <span class="caret"></span>
                    </a>
                    <ul role="menu" class="dropdown-menu">
                        {$members}
                    </ul>
                  </li>
                  {$adimDataTab}
                  <li role="presentation" class="pull-right">
                    <a data-toggle="tab" href="#groupInfo">
                    <u>{$_SESSION['name']}</u>
                    </a>
                  </li>
              </ul>
              <div class="tab-content">
                  {$postForm}
                  <div id="groups" class="tab-pane fade in active">
                    {$posts}
                  </div>
                  {$adimData}
                  {$groupInfo}
              </div>
              {$footer}
            </main>
        </body>
      </html>
    );
    if(($_SESSION['IPAddress'] == $_SERVER['REMOTE_ADDR']) && ($expireTime > date("Y-m-d H:i:s")) && ($_SESSION['BrowserData'] == $_SERVER['HTTP_USER_AGENT'])) {
      return $page;
    } else {
      var_dump($_SESSION);
      return "Sorry something when wrong";
    }
  }

  echo master();
