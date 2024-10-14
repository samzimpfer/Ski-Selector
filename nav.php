<nav>
    <a class="<?php
    if ($pathParts['filename'] == "index") {
        print 'activePage';
    }
    ?>" href="index.php">Choosing the Right Ski</a>

    <a class="<?php
    if ($pathParts['filename'] == "form") {
        print 'activePage';
    }
    ?>" href="form.php">Take the Quiz</a>
</nav>