<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$quizId = $_GET['quizId'] ?? null;
$correctas = $_GET['correctas'] ?? 0;
$total = $_GET['total'] ?? 0;

if (!$quizId) {
    header("Location: quizzes.php");
    exit;
}

$quizzes = file_exists('./json/quizzes.json') ? json_decode(file_get_contents('./json/quizzes.json'), true) : [];
$quiz = array_filter($quizzes, fn($quiz) => $quiz['id'] === $quizId);
$quiz = array_values($quiz)[0] ?? null;
$title = $quiz['title'] ?? "Cuestionario";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="result-page" class="animate__animated animate__fadeIn">
        <h1>Resultados de: <?= htmlspecialchars($title) ?></h1>
        <p>¡Has conseguido <strong><?= $correctas ?></strong> respuestas correctas de un total de <strong><?= $total ?></strong> preguntas!</p>
        <div class="result-buttons">
            <form action="play_quiz.php" method="GET">
                <input type="hidden" name="quizId" value="<?= htmlspecialchars($quizId) ?>">
                <button type="submit">Reintentar</button>
            </form>
            <a href="quizzes.php" class="btn">Volver a los Cuestionarios</a>
        </div>
    </div>
</body>
</html>
