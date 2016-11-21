<?php
  function userImageSrc() {
    require('../model/db.php');

    $query = "select * from person where ((fname = :fName) && (lname = :lName) && (birthday = :bDate))";
    $statement = $db->prepare($query);
    $statement->bindValue(':fName', $_COOKIE['fName']);
    $statement->bindValue(':lName', $_COOKIE['lName']);
    $statement->bindValue(':bDate', $_COOKIE['bDate']);
    $statement->execute();
    $curUser = $statement -> fetch();
    $statement->closeCursor();

    if($curUser['picture'] == null) {
      return "../../public/images/default.jpg";
    } else {
      return "../../uploads/{$curUser['picture']}";
    }
  }

  function userPanel() {
    return "
      <div id=\"userMenu\" class=\"tab-pane fade\">
        <div class=\"panel panel-default\">
          <div class=\"panel-heading\">User</div>
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
                 <tr class=\"success\">
                   <td>{$_COOKIE['fName']} {$_COOKIE['lName']}</td>
                   <td>{$_COOKIE['bDate']}</td>
                 </tr>
               </tbody>
             </table>
           </div>
            <form action=\"../controller/action.php\" method=\"post\" enctype=\"multipart/form-data\">
              <input type=\"hidden\" type=\"text\" name=\"action\" value=\"uploadUserImg\">
              <div class=\"form-group\">
                <label class=\"btn btn-default btn-file\">
                  Upload Picture <input name=\"userNewImage\" type=\"file\" style=\"display: none;\">
                </label>
              </div>
              <button type=\"submit\" class=\"btn btn-default\">Done</button>
            </form>
          </div>
        </div>
      </div>
    ";
  }
?>
