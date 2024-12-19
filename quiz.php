<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "quiz_app"; 


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$questions = [
    [
        "question" => "What is the capital of France?",
        "options" => ["Paris", "Berlin", "Madrid", "Rome"],
        "correct" => 0
    ],
    [
        "question" => "Which language is primarily used for web development?",
        "options" => ["Python", "Java", "PHP", "C#"],
        "correct" => 2
    ],
    [
        "question" => "What is the largest planet in our solar system?",
        "options" => ["Earth", "Mars", "Jupiter", "Saturn"],
        "correct" => 2
    ],
    [
        "question" => "Who wrote 'Romeo and Juliet'?",
        "options" => ["Charles Dickens", "William Shakespeare", "Mark Twain", "J.K. Rowling"],
        "correct" => 1
    ],
    [
        "question" => "What is the boiling point of water in Celsius?",
        "options" => ["90°C", "100°C", "110°C", "120°C"],
        "correct" => 1
    ],
    [
        "question" => "What is the chemical symbol for Gold?",
        "options" => ["Au", "Ag", "Go", "Gd"],
        "correct" => 0
    ],
    [
        "question" => "Which country is known as the Land of the Rising Sun?",
        "options" => ["China", "Japan", "Thailand", "India"],
        "correct" => 1
    ],
    [
        "question" => "What is the largest mammal on Earth?",
        "options" => ["Elephant", "Blue Whale", "Giraffe", "Hippopotamus"],
        "correct" => 1
    ],
    [
        "question" => "How many continents are there in the world?",
        "options" => ["5", "6", "7", "8"],
        "correct" => 2
    ],
    [
        "question" => "What is the smallest prime number?",
        "options" => ["1", "2", "3", "5"],
        "correct" => 1
    ]
];
$totalQuestions = count($questions);
$currentQuestion = isset($_SESSION['currentQuestion']) ? $_SESSION['currentQuestion'] : 0;
$score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;
$name = isset($_SESSION['name']) ? $_SESSION['name'] : null;

f ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        $_SESSION['name'] = $name;
    }

    if (isset($_POST['answer'])) {
        if ($_POST['answer'] == $questions[$currentQuestion]['correct']) {
            $score++;
        }
    }

    $currentQuestion++;
    if ($currentQuestion >= $totalQuestions || isset($_POST['end_quiz'])) {

        $stmt = $conn->prepare("INSERT INTO highscores (name, score) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $score);
        $stmt->execute();
        $stmt->close();


        $finished = true;
        session_destroy();
    } else {

        $_SESSION['currentQuestion'] = $currentQuestion;
        $_SESSION['score'] = $score;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            text-align: center;
        }
        .quiz-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="quiz-container">
    <?php if (isset($finished) && $finished): ?>
        <h2>Quiz Completed!</h2>
        <p>Your score: <strong><?php echo $score; ?></strong> out of <strong><?php echo $totalQuestions; ?></strong></p>
        <h3>Correct Answers:</h3>
        <ul style="text-align: left;">
            <?php foreach ($questions as $index => $question): ?>
                <li>
                    <strong><?php echo $question['question']; ?></strong><br>
                    Correct Answer: <?php echo $question['options'][$question['correct']]; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <h3>Leaderboard</h3>
        <ul style="text-align: left;">
            <?php
            $result = $conn->query("SELECT name, score FROM highscores ORDER BY score DESC, id ASC LIMIT 10");
            while ($row = $result->fetch_assoc()): ?>
                <li><strong><?php echo $row['name']; ?></strong>: <?php echo $row['score']; ?></li>
            <?php endwhile; ?>
        </ul>
        <a href="quiz.php"><button>Restart Quiz</button></a>
    <?php else: ?>
        <?php if (!$name): ?>
            <h2>Welcome to the Quiz!</h2>
            <form method="POST" action="quiz.php">
                <label for="name">Enter your name:</label><br>
                <input type="text" name="name" id="name" required><br><br>
                <button type="submit">Start Quiz</button>
            </form>
        <?php else: ?>
            <h2>Question <?php echo $currentQuestion + 1; ?> of <?php echo $totalQuestions; ?></h2>
            <form method="POST" action="quiz.php">
                <p><strong><?php echo $questions[$currentQuestion]['question']; ?></strong></p>
                <?php foreach ($questions[$currentQuestion]['options'] as $index => $option): ?>
                    <div>
                        <label>
                            <input type="radio" name="answer" value="<?php echo $index; ?>" required>
                            <?php echo $option; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <br>
                <button type="submit">Next</button>
                <button type="submit" name="end_quiz">End Quiz</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
