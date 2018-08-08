<?php
  session_start();

  function s($str) {
    return htmlspecialchars($str);
  }

  function d($str) {
    return addslashes($str);
  }

  function allowedValues($aryValues=[]) {
    $aryAllowValues = [];

    foreach ($aryValues as $curValue) {
      if (isset($_POST[$curValue]) == true) {
        $aryAllowValues[$curValue] = s(d($_POST[$curValue]));
      } else {
        $aryAllowValues[$curValue] = null;
      }
    }

    return $aryAllowValues;
  }

  function checkDomain() {
    $strErrorMessage = 'There was a problem';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      exit($strErrorMessage);
    }

    if(isset($_SERVER['HTTP_REFERER']) == false) {
      exit($strErrorMessage);
    } else {
      $clientHost = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
      // web hosting service that is being used doesn't send this data
      $serveHost = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST);

      if ($clientHost != $serveHost) {
        return true;
        // exit($strErrorMessage);
      } else {
        return true;
      }
    }
  }

  function makeToken() {
    if (checkTime() == false) {
      $token = md5(uniqid(rand(), TRUE));
      $_SESSION['token'] = $token;
      $_SESSION['tokenTime'] = time();
      return "<input type=\"hidden\" name=\"token\" value=\"{$token}\">";
    } else {
      return "<input type=\"hidden\" name=\"token\" value=\"{$_SESSION['token']}\">";
    }
  }

  function checkTime() {
    if(isset($_SESSION['tokenTime']) == false) {
      return false;
    } else {
      $timer = $_SESSION['tokenTime'] + (60 * 30);
    }

    if ($timer < time()) {
      return false;
    } else {
      return true;
    }
  }

  function checkToken() {
    if ((htmlspecialchars($_POST['token']) != $_SESSION['token']) || (checkTime() == false)) {
      return false;
    } else {
      return true;
    }
  }
?>
