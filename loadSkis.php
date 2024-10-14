<?php
// This program loads the 4 best skis for a user given their preferences for what terrain to use the skis on and how to rank skis

include "database-connect.php";

$use = '';
$ranking = '';
if (isset($_GET['u']) && isset($_GET['a'])) {
    $use = $_GET['u'];
    $ranking = $_GET['a'];

    $sql = "SELECT * FROM tblAnswers WHERE fldQuestion='use' AND fldText='" . $use . "'";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $potentialUses = $statement->fetchAll();

    $sql = "SELECT * FROM tblAnswers WHERE fldTarget='ski' AND fldText='" . $ranking . "'";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $potentialRankings = $statement->fetchAll();

    if (count($potentialUses) >= 1 && count($potentialRankings) >= 1) { // validate 'use' and 'ranking' inputs by making sure they are contained in answer list
        $skis = [];

        $table = '';
        $sort = '';
        $direction = '';
        $normalSort = true;

        // determine what table to search for skis
        if ($use == "All-mountain") {
            $table = "tblAllMountainSkis";
        } else if ($use == "Powder") {
            $table = "tblPowderSkis";
        } else if ($use == "Park") {
            $table = "tblParkSkis";
        }

        // determine what to sort skis based on
        if ($ranking == 'Price') {
            $sort = 'fldPrice';
            $direction = 'ASC';
        } else if ($ranking == 'Durability') {
            $sort = 'fldDurability';
            $direction = 'DESC';
        } else if ($ranking == 'Waist-width' && $use == 'Powder') {
            // get all powder skis
            $sql = "SELECT * FROM " . $table;
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $allSkis = $statement->fetchAll();
            
            // store max width of each ski into an array
            $maxWidths = [];
            $maxWidthsSorted = [];
            foreach($allSkis as $s) {
                $widths = json_decode($s['fldWidth']);
                $maxWidths[] = $widths[count($widths)-1];
                $maxWidthsSorted[] = $widths[count($widths)-1];
            }
            rsort($maxWidthsSorted);

            // store top 4 widest skis into an array
            for ($i=0; $i < 4; $i++) {
                $index = array_search($maxWidthsSorted[$i], $maxWidths);
                $skis[] = $allSkis[$index];
            }

            $normalSort = false;
        } else if (($ranking == 'Floatation' || $ranking == 'Playfulness' || $ranking == 'Versatility' || $ranking == 'Crud-performance') && $use == 'Powder') {
            // get all powder skis
            $sql = "SELECT * FROM " . $table;
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $allSkis = $statement->fetchAll();

            // store any ski with matching characteristic to array
            foreach ($allSkis as $s) {
                $c = json_decode($s['fldCharacteristics']);
                if (in_array($ranking, $c)) {
                    $skis[] = $s;
                }
            }

            $normalSort = false;
        } else if ($ranking == 'Swing-weight' && $use == 'Park') {
            $sort = 'fldSwingWeight';
            $direction = 'ASC';
        } else if ($ranking == 'Softer' && $use == 'Park') {
            $sort = 'fldFlex';
            $direction = 'ASC';
        } else if ($ranking == 'Stiffer' && $use == 'Park') {
            $sort = 'fldFlex';
            $direction = 'DESC';
        } else if ($ranking == 'In-between' && $use == 'Park') {
            // store all park skis in array
            $sql = "SELECT * FROM tblParkSkis ORDER BY fldFlex ASC";
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $allSkis = $statement->fetchAll();

            // trim ends of array evenly to total length of 4
            $skis = array_slice($allSkis, (count($allSkis)-4)/2, 4);

            $normalSort = false;
        } else {
            echo "SERVER ERROR: unrecognized combination of usage and ranking.";
        }

        if ($normalSort) {
            $sql = "SELECT * FROM " . $table . " ORDER BY " . $sort . " " . $direction;
            $statement = $pdo->prepare($sql);
            $statement->execute();
            $skis = $statement->fetchAll();
        }


        echo cleanJSON(json_encode(array_slice($skis, 0, 4)));
    } else {
        echo "SERVER ERROR: answer has been corrupted. Try reloading the page.";
    }
} else {
    echo "SERVER ERROR: no data provided. If testing, please provide a query string with 'u=...&a=...'";
}

// strips double quotes around square brackets and backslashes inside square brackets
function cleanJSON($message) {    
    $bracketStart = 0;
    for ($i=1; $i < strlen($message)-1; $i++) { 
        if ($message[$i] == '[') {
            $bracketStart = $i;
        }
        if ($message[$i] == ']') {
            $length = $i - $bracketStart + 1;
            $bracketedPart = substr($message, $bracketStart, $length);
            $deletionCount = substr_count($bracketedPart, "\\");
            $replacement = str_replace("\\", '', $bracketedPart);

            $message = substr($message, 0, $bracketStart-1) . $replacement . substr($message, $i+2, strlen($message)-$i-2);
            $i = $i - $deletionCount-1;
        }
    }

    return $message;
}
?>