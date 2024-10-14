<?php include 'top.php'; ?>
        <main class="formPage">
            <h1>The Quiz</h1>

            <section>
                <form id="form" method="GET" action="postForm.php" onsubmit="return validateForm()">
                    <span id="message"></span>

                    <fieldset id="current-question">
                        <legend id="legend">Default question</legend>

                        <div id="answers">
                            <div id="answer1">
                                <input type="radio" name="question" id="input1" value="Answer1">
                                <label id="label1" for="input1">
                                    <p id="title1"></p>
                                    <figure id="figure1">
                                        <img id="image1" src="" alt="Could not load image.">
                                        <figcaption id="description1"></figcaption>
                                    </figure>
                                </label>
                            </div>

                            <div id="answer2">
                                <input type="radio" name="question" id="input2" value="Answer2">
                                <label id="label2" for="input2">
                                    <p id="title2"></p>
                                    <figure id="figure2">
                                        <img id="image2" src="" alt="Could not load image.">
                                        <figcaption id="description2"></figcaption>
                                    </figure>
                                </label>
                            </div>

                            <div id="answer3">
                                <input type="radio" name="question" id="input3" value="Answer3">
                                <label id="label3" for="input3">
                                    <p id="title3"></p>
                                    <figure id="figure3">
                                        <img id="image3" src="" alt="Could not load image.">
                                        <figcaption id="description3"></figcaption>
                                    </figure>
                                </label>
                            </div>

                            <div id="answer4">
                                <input type="radio" name="question" id="input4" value="Answer4">
                                <label id="label4" for="input4">
                                    <p id="title4"></p>
                                    <figure id="figure4">
                                        <img id="image4" src="" alt="Could not load image.">
                                        <figcaption id="description4"></figcaption>
                                    </figure>
                                </label>
                            </div>
                        </div>

                        <input type="submit" name="btnSubmit" id="btnSubmit" value="Next">

                        <input type="hidden" name="table" id="tableInput" value="">
                    </fieldset>
                </form>
            </section>

            <script src=form.js></script>
        </main>
<?php include 'footer.php'; ?>