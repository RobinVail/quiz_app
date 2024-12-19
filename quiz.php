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
