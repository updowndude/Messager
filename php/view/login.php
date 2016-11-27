<?php
  function loginForm() {
    // require('../../controller/defense.php');
    $token = makeToken();

    return "<form id=\"indexForm\" action=\"php/controller/action.php\" method=\"post\">
      <input type=\"hidden\" type=\"text\" name=\"action\" value=\"done\">
      <div class=\"form-group\">
        <label for=\"fName\">First Name</label>
        <input type=\"text\" class=\"form-control\" name=\"fName\">
      </div>
      <div class=\"form-group\">
        <label for=\"lName\">Last Name</label>
        <input type=\"text\" class=\"form-control\" name=\"lName\">
      </div>
      <div class=\"form-group\">
        <label for=\"lName\">Birthday</label>
        <input type=\"text\" class=\"form-control bDatePlace\" placeholder=\"YYYY-MM-DD\" name=\"bDate\">
      </div>
      {$token}
      <button type=\"submit\" class=\"btn btn-info\" disabled>Submit</button>
    </form>";
  }
?>
