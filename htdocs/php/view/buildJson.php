<?hh
  function builder() {
    require('php/model/db.php');

    $newFeedback = handleSQL('select * from groups', [], [], 1);

    $file = fopen('../../json/groups.json', 'w');
    fwrite($file, json_encode($newFeedback));
    fclose($file);
  }
