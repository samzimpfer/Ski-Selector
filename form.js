// these classes will store question/answer data
class Question {
    constructor(name, number, text) {
        this.name = name;
        this.number = number;
        this.text = text;
        this.answers = [];
    }

    addAnswer(a) {
        this.answers.push(a);
    }

    getAnswerFromText(text) {
        for (let a of this.answers) {
            if (a.text == text) {
                return a;
            }
        }
        return null;
    }
}

class Answer {
    constructor(text, description, target) {
        this.text = text;
        this.description = description;
        this.target = target;
    }
}

// variables
let message;
let form;
let legend;
const answers = [];
const inputs = [];
const labels = [];
const titles = [];
const figures = [];
const images = [];
const descriptions = [];

const questions = new Map();
let currentQuestion;
let skiUse;

// program start
window.addEventListener("DOMContentLoaded", function() {
    console.log("HTML content loaded");

    loadQuestionDOM();
    loadQuestionDataAndLaunch();
});

// loads DOM references to HTML
function loadQuestionDOM() {
    message = document.getElementById("message");
    form = document.getElementById("form");
    legend = document.getElementById("legend");

    answers.push(document.getElementById("answer1"));
    answers.push(document.getElementById("answer2"));
    answers.push(document.getElementById("answer3"));
    answers.push(document.getElementById("answer4"));

    inputs.push(document.getElementById("input1"));
    inputs.push(document.getElementById("input2"));
    inputs.push(document.getElementById("input3"));
    inputs.push(document.getElementById("input4"));
    
    labels.push(document.getElementById("label1"));
    labels.push(document.getElementById("label2"));
    labels.push(document.getElementById("label3"));
    labels.push(document.getElementById("label4"));

    titles.push(document.getElementById("title1"));
    titles.push(document.getElementById("title2"));
    titles.push(document.getElementById("title3"));
    titles.push(document.getElementById("title4"));
    
    figures.push(document.getElementById("figure1"));
    figures.push(document.getElementById("figure2"));
    figures.push(document.getElementById("figure3"));
    figures.push(document.getElementById("figure4"));

    images.push(document.getElementById("image1"));
    images.push(document.getElementById("image2"));
    images.push(document.getElementById("image3"));
    images.push(document.getElementById("image4"));

    descriptions.push(document.getElementById("description1"));
    descriptions.push(document.getElementById("description2"));
    descriptions.push(document.getElementById("description3"));
    descriptions.push(document.getElementById("description4"));
}

// returns a new Question object based on an abitrary object recieved from JSON
function createQuestionFromData(data) {
    let question = new Question(data.name, data.number, data.text);
    if (data.answers != null) {
        for (const a of data.answers) {
            question.addAnswer(new Answer(a.text, null, a.target));
        }
    }

    return question;
}

// returns a new Answer object containing data to display a ski
function createSkiAnswer(data) {
    let lengths = JSON.stringify(data['fldLength']);
    let widths = JSON.stringify(data['fldWidth']);
    let name = data['fldName'];
    let description = "Available lengths (cm): " + lengths + "<br>Available widths (cm): " + widths + "<br>Price: $" + data['fldPrice'] + "<br>Durability: " + data['fldDurability'] + "/10";
    let answer = new Answer(name, description.replace(/[\[\]]/g, ""), null);

    return answer;
}

// loads question data from database and launches the first question
function loadQuestionDataAndLaunch() {
    displayMessage("Loading quiz...");

    // call php program to retrieve database entries and store results in array of objects
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        let response = xmlhttp.responseText;
        let questionData = JSON.parse(response); // this is an array of generic objects created in php and retrieved via JSON

        // copy object data into a map of Question objects
        for (const data of questionData) {
            let q = createQuestionFromData(data);
            questions.set(q.name, q);

            // assign currentQuestion to the root question
            if (data.number == 1) {
                currentQuestion = q;
            }
        }

        // display the root question when finished
        displayQuestion(currentQuestion);
    }
    xmlhttp.open("GET", "loadQuestionData.php");
    xmlhttp.send();
}

// loads the 4 best skis based on what the user has entered in the form
function loadSkisAndDisplay(use, answerText) {
    displayMessage("Loading skis...");

    // call php program to retrieve database entries and store results in array of objects
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        let skiData = JSON.parse(this.responseText); // this is an array of generic objects created in php and retrieved via JSON
        
        // copy object data into current question Answer objects
        for (const data of skiData) {
            let a = createSkiAnswer(data);
            currentQuestion.addAnswer(a);
        }

        clearMessage();
        displayQuestion(currentQuestion);
    }
    xmlhttp.open("GET", "loadSkis.php?u=" + use + "&a=" + answerText);
    xmlhttp.send();
}

// uses DOM to assign current question data to html form
function displayQuestion(questionToDisplay) {
    displayMessage("Loading next question...");

    // display question
    legend.innerHTML = questionToDisplay.text;
    let a = questionToDisplay.answers;

    // search database for data under the question name    
    let stickyAnswer = "";
    let hasStickyAnswer = true;

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        stickyAnswer = this.responseText;
        if (stickyAnswer == "") {
            hasStickyAnswer = false;
        }

        // display answers
        for (let i=0; i<4; i++) {
            if (i < a.length) {
                // write data from Answer objects to HTML
                answers[i].className = "";
                titles[i].innerHTML = a[i].text;
                inputs[i].value = a[i].text;

                if (questionToDisplay.name == "ski") { // display skis with images + descriptions
                    titles[i].className = "image-title";
                    figures[i].className = "";

                    images[i].src = "images/" + a[i].text + ".jpg";
                    descriptions[i].innerHTML = a[i].description;
                } else { // display simple text answers
                    titles[i].className = "text-title";
                    figures[i].className = "hide";
                }

                // if any data exists for a sticky response then select that response
                if (hasStickyAnswer && a[i].text == stickyAnswer) {
                    inputs[i].checked = true;
                }
            } else { // hide any answers in HTML that aren't being used by current question
                answers[i].className = "hide";
            }
        }

        clearMessage();
    }
    xmlhttp.open("GET", "loadStickyAnswer.php?q=" + questionToDisplay.name);
    xmlhttp.send();
}

// displays an error message
function displayError(text) {
    message.innerHTML = text;
    message.className = "mistake";

    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

// displays a generic message
function displayMessage(text) {
    message.innerHTML = text;
    message.className = "";

    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

// clears the current message
function clearMessage() {
    message.innerHTML = "";
    message.className = "";
}

// validates user choice in javascript then in php and advances form to the next question if valid
function validateForm() {
    displayMessage("Processing answer...");

    if (input1.checked || input2.checked || input3.checked || input4.checked) { // make sure one answer is selected
        // get Answer object that corresponds to user-given answer
        let answerText = form["question"].value;
        let answer = currentQuestion.getAnswerFromText(answerText);

        if (answer != null) { // JS sanitization
            if (currentQuestion.name != "ski") { // php sanitization and storing unless current question is 'which ski'
                let response;
                let xmlhttp = new XMLHttpRequest();
                xmlhttp.onload = function() {
                    if (this.responseText == "1") {
                        clearMessage();

                        return advanceQuestion(answer);
                    } else {
                        displayError(this.responseText);
                    }   
                }
                xmlhttp.open("GET", "sanitizeAndStore.php?q=" + currentQuestion.name + "&a=" + answerText);
                xmlhttp.send();

            } else { // allow form action to launch if current question is which ski
                // keep track of which table the ski came from
                let table = "";
                if (skiUse == "All-mountain") {
                    table = "tblAllMountainSkis";
                } else if (skiUse == "Powder") {
                    table = "tblPowderSkis";
                } else if (skiUse == "Park") {
                    table = "tblParkSkis";
                }
                form["table"].value = table;
                
                return true;
            }
        } else {
            displayError("Bad form data. Try reloading the page.");
        }
    } else {
        displayError("Please select one of the answers.");
    }

    return false;  // prevent page from reloading after each question
}

// advances form to the next question
function advanceQuestion(answer) {
    // save ski usage preference from first question
    if (currentQuestion.name == 'use') {
        skiUse = answer.text;
    }

    // clear selected inputs
    form.reset();

    // advance to next question
    currentQuestion = questions.get(answer.target);
    if (currentQuestion.name == "ski") {
        loadSkisAndDisplay(skiUse, answer.text);
    } else {
        displayQuestion(currentQuestion);
    }

    return false;  // prevent page from reloading after each question
}

// TODO: phone css
// TODO: better error reporting