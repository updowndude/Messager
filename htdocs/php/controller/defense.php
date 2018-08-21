<?hh
/**
 * Created by PhpStorm.
 * User: correywinke
 * Date: 3/7/17
 * Time: 11:19 AM
 */
//only selected value are accepted
function allowedValues($aryValues=[]) {
    // makes emptry array
    $aryAllowValues = [];
    // loops throught the values submit
    foreach ($aryValues as $curValue) {
        // if that value makes what is in the post it's ok
        if (isset($_POST[$curValue]) == true) {
            // secure the value
            $aryAllowValues[$curValue] = htmlspecialchars(addslashes($_POST[$curValue]));
        } else {
            // makes that nothing
            $aryAllowValues[$curValue] = null;
        }
    }
    // get back the value
    return $aryAllowValues;
}
// generates a random token
function makeToken() {
    // see how long the person been longed in
    if (checkTime() == false) {
        // random token
        $token = md5(uniqid((string) rand(), TRUE));
        $_SESSION['token'] = $token;
        $_SESSION['tokenTime'] = time();
        // makes the new token
        return $token;
    } else {
        // display the token with the current value
        return $_SESSION['token'];
    }
}
// check to if not been to long
function checkTime() {
    // no time has stared
    if(isset($_SESSION['tokenTime']) == false) {
        return false;
    } else {
        // makes the varible to store the tiem when created
        $timer = $_SESSION['tokenTime'] + (60 * 60);
    }
    // check the time to see been to long
    if ($timer < time()) {
        return false;
    } else {
        // update toke time the user is active
         $_SESSION['tokenTime'] = time();
        return true;
    }
}
// checks the token
function checkToken() {
    // see the session and client token are same and time is the same
    if (isset($_SESSION['token']) == true) {
        if ((htmlspecialchars((string) $_POST['token']) != $_SESSION['token']) || (checkTime() == false)) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}
