<?php include 'top.php'; ?>
        <main class="postForm">
            <h1>The Quiz</h1>

            <h2>Thank you for your submission!</h2>

            <section>
                <p>You chose the 
                <?php 
                    $ski = '';
                    if (isset($_GET['question'])) {
                        $ski = $_GET['question'];
                    }
                    echo $ski;
                ?>
                </p>

                <figure>
                    <img alt=<?php echo $ski ?> src="images/<?php echo $ski ?>.jpg">
                </figure>

                <p>
                <?php
                    include "database-connect.php";

                    $link = '';
                    
                    if (isset($_GET['table'])) {
                        $sql = "SELECT fldLink FROM " . $_GET['table'] . " WHERE fldName='" . $ski . "'";
                        $statement = $pdo->prepare($sql);
                        $statement->execute();
                        $entries = $statement->fetchAll();

                        if (count($entries) == 1) {
                            $link = $entries[0]['fldLink'];
                        }
                    }

                    if ($link != '') {
                        echo 'Here is a link to purchase your ski: <a href="' . $link . '" target="_blank">' . $ski . '</a>';
                    } else {
                        echo 'SERVER ERROR: could not retrieve link to purchase.';
                    }
                ?>
                </p>
            </section>
        </main>
<?php include 'footer.php'; ?>