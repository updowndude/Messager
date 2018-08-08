<?php
  function userImageSrc() {
    require('../model/db.php');

    $query = "select * from person where ((fname = :fName) && (lname = :lName) && (birthday = :bDate))";
    $statement = $db->prepare($query);
    if (!$statement) {
      exit("Sorry prepare failed");
    }
    $bind_results = $statement->bindValue(':fName', $_SESSION['fName']);
    if(!$bind_results) {
      exit("Sorry can't bind those value");
    }
    $bind_results = $statement->bindValue(':lName', $_SESSION['lName']);
    if(!$bind_results) {
      exit("Sorry can't bind those value");
    }
    $bind_results = $statement->bindValue(':bDate', $_SESSION['bDate']);
    if(!$bind_results) {
      exit("Sorry can't bind those value");
    }
    $workQuery = $statement->execute();
    if(!$workQuery) {
      exit("Bad execcution");
    }
    $curUser = $statement -> fetch();
    $statement->closeCursor();

    if($curUser['picture'] == null) {
      return "../../public/images/default.jpg";
    } else {
      return "../../uploads/{$curUser['picture']}";
    }
  }

  function userPanel() {
    // require('../controller/defense.php');
    $token = makeToken();

    return "
      <div id=\"userMenu\" class=\"tab-pane fade\">
        <div class=\"panel panel-info\">
          <div class=\"panel-heading\">
            <p class=\"text-warning\">User</p>
          </div>
          <div class=\"panel-body\">
                <div class=\"table-responsive\">
             <table class=\"table table-hover\">
               <thead>
                 <tr>
                   <th>Name</th>
                   <th>Brithday</th>
                 </tr>
               </thead>
               <tbody>
                 <tr class=\"info\">
                   <td><mark>{$_SESSION['fName']} {$_SESSION['lName']}</mark></td>
                   <td>{$_SESSION['bDate']}</td>
                 </tr>
               </tbody>
             </table>
           </div>
            <form action=\"../controller/action.php\" method=\"post\" enctype=\"multipart/form-data\">
              <input type=\"hidden\" type=\"text\" name=\"action\" value=\"uploadUserImg\">
              <div class=\"form-group\">
                <label>Upload Picture</label>
                <input class=\"btn btn-info\" name=\"userNewImage\" type=\"file\">              </div>
              {$token}
              <button type=\"submit\" class=\"btn btn-info\">Done</button>
            </form>
          </div>
        </div>
      </div>
    ";
  }
?>
