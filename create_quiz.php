<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $questions = $_POST['questions'] ?? [];

    foreach ($questions as $index => $question) {
        if (empty($question['text']) || empty($question['correctAnswer']) || count($question['answers']) < 2) {
            echo "La pregunta #$index no está completa.";
            exit;
        }
    }

    $quiz = [
        'id' => uniqid(),
        'creator' => $_SESSION['username'],
        'title' => $title,
        'questions' => $questions
    ];

    $quizzes = file_exists('./json/quizzes.json') ? json_decode(file_get_contents('./json/quizzes.json'), true) : [];
    $quizzes[] = $quiz;
    file_put_contents('./json/quizzes.json', json_encode($quizzes, JSON_PRETTY_PRINT));

    // Set a flash message in the session
    $_SESSION['flash_message'] = "¡Cuestionario creado con éxito!";

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuestionario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="creator" class="animate__animated animate__fadeIn">
        <h1>Crear Nuevo Cuestionario</h1>
        <form action="create_quiz.php" method="POST">
            <label>Título del Cuestionario:</label>
            <input type="text" name="title" placeholder="Título" required>
            <div id="questions"></div>
            <button type="button" onclick="addQuestion()" required>Añadir Pregunta</button>
            <button type="submit">Guardar Cuestionario</button>
        </form>
        <a href="dashboard.php">Volver al Dashboard</a>
    </div>
    <script src="script.js"></script>
</body>
</html>
