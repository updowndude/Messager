<?php
  require_once('php/view/login.php');

  function contentIndex() {
    $login = loginForm();

    return "<div class=\"container\">
      <div class=\"panel panel-info topPanel\">
        <div class=\"panel-heading\"><p class=\"text-warning\">Welcome</p></div>
        <div class=\"panel-body\">
          <article style=\"display: none;\">
            {$login}
          </article>
          <article>
          <div class=\"jumbotron\">
          <div class=\"media\">
            <a class=\"media-left\" href=\"#\">
              <img class=\"media-object\" src=\"public/images/messagerLog.png\" alt=\"Messager logo\">
            </a>
          <div class=\"media-body\">
            <div class=\"page-header\">
              <h1 class=\"media-heading\">Welcome to Messenger</h1>
            </div>
            <p>
              Stay connect with one another.
            </p>
          </div>
          </div>
          </div>
        </div>
        <div class=\"panel-footer\">
          <div class=\"btn-group\">
            <button type=\"button\" class=\"btn btn-info helloDartBtn1\">Login</button>
            <button type=\"button\" class=\"btn btn-info helloDartBtn2 active\">Welecome message</button>
          </div>
        </div>
        </article>
      </div>
    </div>";
  }
?>
