<?php
$phpSelf = htmlspecialchars($_SERVER['PHP_SELF']);
$pathParts = pathinfo($phpSelf);
?>
<!DOCTYPE HTML>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Ski Selector</title>
        <meta name="author" content="Sam Zimpfer">
        <meta name="description" content="It is important to do your research and choose the right ski. This program will help you narrow down your choices.">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/custom.css?version=<?php print time(); ?>" 
                               rel="stylesheet"
                               type="text/css">
        <link rel="stylesheet" href="css/layout-desktop.css?version=<?php print time(); ?>" 
                               rel="stylesheet" 
                               type="text/css">
        <link rel="stylesheet" href="css/layout-tablet.css?version=<?php print time(); ?>" 
                               media="(max-width: 820px)"
                               rel="stylesheet"
                               type="text/css">
        <link rel="stylesheet" href="css/layout-phone.css?version=<?php print time(); ?>" 
                               media="(max-width: 420px)"
                               rel="stylesheet"
                               type="text/css">
    </head>

    <?php
    print '<body class="' . $pathParts['fileName'] . '">';

    include 'header.php';
    include 'nav.php';
    ?>