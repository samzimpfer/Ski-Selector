<?php
// This program recieves the values of 'q', the question name and 'a', the answer given by the user from a URL query string.
// It tests first of all whether the question exists in the database and second of all whether the answer is in the database
// and belongs to the specified question. If all tests are passed the answer is stored to a database record

include "database-connect.php";

// get question name and given answer from URL
$questionName = '';
$givenAnswer = '';
if (isset($_GET['q']) && isset($_GET['a'])) {
    $questionName = $_GET['q'];
    $givenAnswer = $_GET['a'];

    // verify question in database
    $sql = "SELECT fldName FROM tblQuestions WHERE fldName='" . $questionName . "'";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $potentialQuestion = $statement->fetchAll();

    if (count($potentialQuestion) >= 1) {
        // verify answer in database
        $sql = "SELECT fldText FROM tblAnswers WHERE fldText='" . $givenAnswer . "' AND fldQuestion='" . $questionName . "'";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $potentialAnswer = $statement->fetchAll();

        if (count($potentialAnswer) >= 1) {
            // store response and echo true if everything works

            // delete other data if the question already has responses saved
            $sql = "DELETE FROM tblSavedResponses WHERE fldQuestionName='" . $questionName . "'";
            $statement = $pdo->prepare($sql);
            $statement->execute();

            // store newest response to database
            $sql = "INSERT INTO tblSavedResponses (fldQuestionName, fldAnswer) VALUES (?, ?)";
            $data = array($questionName, $givenAnswer);

            try {
                $statement = $pdo->prepare($sql);
                if ($statement->execute($data)) {
                    echo "1";
                } else {
                    echo "SERVER ERROR: could not save response. Please try again.";
                }
            } catch (PDOException $e) {
                echo "SERVER ERROR: could not save response. Please contact someone.";
            }            
        } else {
            echo "SERVER ERROR: answer has been corrupted.";
        }
    } else {
        echo "SERVER ERROR: question has been corrupted";
    }
} else {
    echo "SERVER ERROR: question data lost. If testing, please provide a query string with 'q=...&a=...'";
}
?>