<?hh
  function feedbackData() {
    // require('../controller/defense.php');
    require('../model/db.php');
    $token = makeToken();
    $toGroups = [<option value="-1"> </option>];
    $toRating = [];
    $lcver = "";

    $newFeedback = handleSQL("select *
      from (person inner join poeple_group on person.person_id = poeple_group.person_id)
        join groups on groups.groups_id = poeple_group.groups_id
      where ((fname = '{$_SESSION['fName']}') && (lname = '{$_SESSION['lName']}') && (birthday = '{$_SESSION['bDate']}'))", [], [], 1);

    foreach($newFeedback as $curFeedback) {
      array_push($toGroups,
        <option value={$curFeedback['groups_id']}>{$curFeedback['name']}</option>
      );
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
      array_push($toRating, <option value={(string) $lcv}>{$lcver}</option>);
    }

    return (
      <div id="feedbackMenu" class="tab-pane fade container">
        <div class="panel panel-info">
            <div class="panel-heading">
              <p class="text-warning">Feedback</p>
            </div>
            <div class="panel-body">
              <form id="feedBack" action="../controller/action.php" method="post">
                  <input type="hidden" name="action" value="giveFeedback"/>
                  <div class="form-group">
                    <label for="selectGroupFeedback">Select a group</label>
                    <select class="form-control" name="selectGroupFeedback">
                    {$toGroups}
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="Name">Rating</label>
                    <select class="form-control" name="rating">
                    {$toRating}
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="txtMessage">Message</label>
                    <textarea class="form-control" name="txtMessage"></textarea>
                  </div>
                  <input type="hidden" name="token" value={$token} />
                  <button type="submit" class="btn btn-info">Submit</button>
              </form>
            </div>
        </div>
      </div>
    );
  }
