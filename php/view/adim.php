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

    $query = "SELECT CONCAT(person.fname,' ', person.lname) as fullPerson, person.birthday ,feedback.message, feedback.rating, groups.name, feedback.placed
        from (person INNER JOIN feedback on person.person_id = feedback.person_id) LEFT JOIN groups on groups.groups_id = feedback.groups_id";
    $statement = $db->prepare($query);
    $statement->execute();
    $newFeedback = $statement -> fetchAll();
    $statement->closeCursor();

    foreach($newFeedback as $curFeedback) {
      $toGroups = $toGroups."
       <tr class=\"success\">
         <td><mark>{$curFeedback['fullPerson']}</mark></td>
         <td>{$curFeedback['birthday']}</td>
         <td>{$curFeedback['message']}</td>
         <td>{$curFeedback['rating']}</td>
         <td>{$curFeedback['name']}</td>
         <td>{$curFeedback['placed']}</td>
       </tr>
      ";
    }

    return "
      <h3>All of feedback</h3>
      {$toGroups}
    ";
  }

  function adimData() {
    $access = getAccess();
    $data = getData();
    $panelData = "";

    if(isset($_COOKIE['adim']) == true) {
      if($_COOKIE['adim'] == 'cVU7k1hstJ') {
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
            <button type=\"submit\" class=\"btn btn-default\">Submit</button>
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
          <button type=\"submit\" class=\"btn btn-default\">Submit</button>
        </form>
      ";
    }

    $values = "
      <div id=\"adimMenu\" class=\"tab-pane fade\">
        <div class=\"panel panel-default\">
          <div class=\"panel-heading\">Adim</div>
          <div class=\"panel-body\">
            {$panelData}
          </div>
        </div>
      </div>
    ";

    return $values;
  }
?>
