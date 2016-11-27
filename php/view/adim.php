<?php
  function getAccess() {
    return "
      <p>As <abbr title=\"Administrator\">Admin</abbr></p>
      <ol>
        <li>Access to all of the groups</li>
        <li>Delete group and post</li>
        <li>Special data</li>
      </ol>
    ";
  }

  function getData() {
    require('../model/db.php');

    $toGroups = "";
    $cnt = 2;

    $query = "SELECT CONCAT(person.fname,' ', person.lname) as fullPerson, person.birthday ,feedback.message, feedback.rating, groups.name, feedback.placed
        from (person INNER JOIN feedback on person.person_id = feedback.person_id) LEFT JOIN groups on groups.groups_id = feedback.groups_id";
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
      if($cnt % 2 == 0) {
        $row = "info";
      } else {
        $row = "danger";
      }

      $toGroups = $toGroups."
       <tr class=\"{$row}\">
         <td><mark>{$curFeedback['fullPerson']}</mark></td>
         <td>{$curFeedback['birthday']}</td>
         <td>{$curFeedback['message']}</td>
         <td>{$curFeedback['rating']}</td>
         <td>{$curFeedback['name']}</td>
         <td>{$curFeedback['placed']}</td>
       </tr>
      ";

      $cnt++;
    }

    return "
      <h3>All of feedback</h3>
      {$toGroups}
    ";
  }

  function adimData() {
    // require('../controller/defense.php');
    $token = makeToken();
    $access = getAccess();
    $data = getData();
    $panelData = "";

    if(isset($_SESSION['adim']) == true) {
      if($_SESSION['adim'] == 'cVU7k1hstJ') {
        $panelData = "
          {$access}
          <hr />
          <div class=\"table-responsive\">
           <table class=\"table table-hover\">
             <thead>
               <tr>
                 <th>Name</th>
                 <th>Birthday</th>
                 <th>Message</th>
                 <th>Rating</th>
                 <th>Group Name</th>
                 <th>Posted</th>
               </tr>
             </thead>
             <tbody>
          {$data}
          </tbody>
        </table>
      </div>
        ";
      } else {
        $panelData = "
          <form id=\"adimEnter\" action=\"../controller/action.php\" method=\"post\">
            <input type=\"hidden\" type=\"text\" name=\"action\" value=\"adim\">
            <div class=\"form-group\">
              <label for=\"selectGroupFeedback\"><abbr title=\"Administrator\">Admin</abbr> Key</label>
              <input class=\"adimSearch\" type=\"text\" type=\"text\" name=\"adimString\">
            </div>
            {$token}
            <button type=\"submit\" class=\"btn btn-info\">Submit</button>
          </form>
        ";
      }
    } else {
      $panelData = "
        <form id=\"adimEnter\" action=\"../controller/action.php\" method=\"post\">
          <input type=\"hidden\" type=\"text\" name=\"action\" value=\"adim\">
          <div class=\"form-group\">
            <label for=\"selectGroupFeedback\"><abbr title=\"Administrator\">Admin</abbr> Key</label>
            <input class=\"adimSearch\" type=\"text\" type=\"text\" name=\"adimString\">
          </div>
          {$token}
          <button type=\"submit\" class=\"btn btn-info\">Submit</button>
        </form>
      ";
    }

    $values = "
      <div id=\"adimMenu\" class=\"tab-pane fade\">
        <div class=\"panel panel-info\">
          <div class=\"panel-heading\">
            <p class=\"text-warning\">Adim</p>
          </div>
          <div class=\"panel-body\">
            {$panelData}
          </div>
        </div>
      </div>
    ";

    return $values;
  }
?>
