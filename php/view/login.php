<?php
  function loginForm() {
    return "<form action=\"php/controller/action.php\" method=\"post\">
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
        <input type=\"text\" class=\"form-control\" name=\"bDate\">
      </div>
      <button type=\"submit\" class=\"btn btn-default\">Submit</button>
    </form>";
  }
?>
