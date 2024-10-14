<?php
// this program returns the most recent answer to a given question specified in the URL

include "database-connect.php";

$questionName = '';
$answer = '';
if (isset($_GET['q'])) {
    $questionName = $_GET['q'];

    $sql = "SELECT * FROM tblSavedResponses WHERE fldQuestionName='" . $questionName . "'";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $entries = $statement->fetchAll();

    if (count($entries) > 0) {
        $answer = $entries[0]['fldAnswer'];

        if ($answer != null && $answer != '') {
            echo $answer;
        } else {
            echo "SERVER ERROR: bad data for saved response.";
        }
    }
} else {
    echo "SERVER ERROR: could not locate saved response. If testing, please provide a query string with 'q=...'";
}
?>