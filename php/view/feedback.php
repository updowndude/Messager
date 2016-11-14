<?php
  function feedbackData() {
    require('../model/db.php');

    $toGroups = "<option value=\"-1\"> </option>";
    $toRating = "";
    $lcver = "";

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

    for($lcv = 1;$lcv <= 5; $lcv++ ) {

      switch ($lcv) {
        case 1:
          $lcver = $lcv." Horrible";
          break;
        case 2:
          $lcver = $lcv." Bad";
          break;
        case 3:
          $lcver = $lcv." OK";
          break;
        case 4:
          $lcver = $lcv." Good";
          break;
        case 5:
          $lcver = $lcv." Great";
          break;
      }


      $toRating = $toRating."
        <option value=\"{$lcv}\">{$lcver}</option>
      ";
    }

    return "
      <div id=\"feedbackMenu\" class=\"tab-pane fade container\">
        <div class=\"panel panel-default\">
          <div class=\"panel-heading\">About Project</div>
          <div class=\"panel-body\">
            <form id=\"feedBack\" action=\"../controller/action.php\" method=\"post\">
              <input type=\"hidden\" type=\"text\" name=\"action\" value=\"giveFeedback\">
              <div class=\"form-group\">
                <label for=\"selectGroupFeedback\">Select a group</label>
                <select class=\"form-control\" name=\"selectGroupFeedback\">
                  {$toGroups}
                </select>
              </div>
              <div class=\"form-group\">
                <label for=\"Name\">Rating</label>
                <select class=\"form-control\" name=\"rating\">
                  {$toRating}
                </select>
              </div>
              <div class=\"form-group\">
                <label for=\"txtMessage\">Message</label>
                <textarea class=\"form-control\" rows=\"5\" name=\"txtMessage\"></textarea>
              </div>
              <button type=\"submit\" class=\"btn btn-default\" disabled>Submit</button>
            </form>
          </div>
        </div>
      </div>
    ";
  }
?>
