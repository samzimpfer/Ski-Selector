<?php 
// This program retrieves question and answer data from a database and builds Question objects with nested Answer objects
// using the data. These objects are to be returned to form.js via JSON

// create template for question data to be stored in a list of Question objects
$questions = [];
class Question {
    public $name;
    public $number;
    public $text;
    public $answers;

    function __construct($name, $number, $text) {
        $this->name = $name;
        $this->number = $number;
        $this->text = $text;
    }

    function addAnswer($a) {
        $this->answers[] = $a;
    }
}

class Answer {
    public $text;
    public $target;

    function __construct($text, $target) {
        $this->text = $text;
        $this->target = $target;
    }
}

include "database-connect.php";

// fetch all question data from database
$sql = "SELECT * FROM tblQuestions";
$statement = $pdo->prepare($sql);
$statement->execute();
$questionData = $statement->fetchAll();

// build Question objects from question data
foreach($questionData as $q) {
    $question = new Question($q['fldName'], $q['fldNumber'], $q['fldText']);

    // fetch answer data for current Question object
    $sql = "SELECT * FROM tblAnswers WHERE fldQuestion='" . $question->name . "'";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $answerData = $statement->fetchAll();

    // build Answer objects and store in current Question object
    foreach($answerData as $a) {
        $answer = new Answer($a['fldText'], $a['fldTarget']);
        $question->addAnswer($answer);
    }

    $questions[] = $question;
}

echo json_encode($questions);
?>