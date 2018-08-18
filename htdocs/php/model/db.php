<?hh
if(!isset($_SESSION)) {
    session_start();
}
/*Summary:
  *Returns a PDO object that has access to the festival database.
  */
function getAccess() {
    // by Correy Winke
    // 10/27/16
    // check to se it works
    try {
        $db = new PDO("mysql:host=localhost;dbname=cwinkebt_Messenger", 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $err) {
        // bad connection
        $_SESSION['errorMessage'] = $err->getMessage();
        // action value didn't match
        // header('Location: error');
    }
}
/*Summary:
  *Takes the required parts to query a database through a PDO object
  *and inserts those things into working code.
  */
/*Remarks:
  *Function provides some error feedback.
  */
/*Parameters:
  * ($strQuer) - The MySQL query as a string (i.e. SELECT * FROM someTable WHERE someTable = :someValue)
  * ($aryStatments) - Array of each thing in the MySQL query that looks like this (element example: ":someSQLVariablePlaceholder")
  * ($aryValues) - Array of each value assigned to each element in '$aryStatments' (element example: 1, "a string", true)
  * ($intGetValues) - Value determining if a single or all rows of fetched data is returned.
  *                      If value = 0, then only a single row is returned
  *                      If value = 1, then all rows are returned
  */
/*RETURNS:
  * (successfully executed query that returns data) --> row[???] or rows[???][???], depending on the last argument's value
  * (successfully executed query designed to retrieve no data) --> null
  * (faild query execution) --> nothing... error message is shown and function stops
  */
function handleSQL($strQuer="",$aryStatments=[], $aryValues=[], $intGetValues = 0) {
    $db = getAccess();
    // can't connect to the database
    if(is_null($db) == true) {
      echo   "<strong>{$_SESSION['errorMessage']}</strong> <hr>  contact Ink-Smart at (608) 313-0506 <hr>";
    } else {
      if((gettype($strQuer) == "string") && (gettype($intGetValues) == "integer") && (gettype($aryValues) == "array") && (gettype($aryStatments) == "array")) {
          if ((count($aryStatments) == count($aryValues)) && (strlen($strQuer) != 0)) {
              $statement = $db->prepare($strQuer);
              if (!$statement) {
                  // bad connection
                  $_SESSION['errorMessage'] = "Sorry prepare failed";
                  // action value didn't match
                header('Location: error');
                exit();
              }
              for($lcv = 0;$lcv < count($aryStatments);$lcv++){
                  $bind_results = $statement->bindValue($aryStatments[$lcv], $aryValues[$lcv]);
                  if(!$bind_results) {
                      // bad connection
                      $_SESSION['errorMessage'] = "Sorry can't bind these value";
                      // action value didn't match
                    header('Location: error');
                    exit();
                  }
              }
              try {
                  $workQuery = $statement->execute();
                  if(!$workQuery) {
                      // bad connection
                      $_SESSION['errorMessage'] = "Bad execcution";
                      // action value didn't match
                        header('Location: error');
                        exit();
                  }
              } catch (Exception $e)  {
                  // bad connection
                  $_SESSION['errorMessage'] = $e;
                  // action value didn't match
                    header('Location: error');
                    exit();
              }
              if ($intGetValues == 0){
                  $newFeedback = $statement -> fetch();
              } elseif ($intGetValues == 1) {
                  $newFeedback = $statement -> fetchAll();
              } else {
                  $newFeedback = "";
              }
              $statement->closeCursor();
              return $newFeedback;
          } else {
              // bad connection
              $_SESSION['errorMessage'] = "Something happen";
              // action value didn't match
            header('Location: error');
            exit();
          }
      } else {
          // bad connection
          $_SESSION['errorMessage'] = "Something happen";
          // action value didn't match
        header('Location: error');
        exit();
      }
    }
}