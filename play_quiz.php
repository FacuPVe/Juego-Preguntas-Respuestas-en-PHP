<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$quizzes = file_exists('./json/quizzes.json') ? json_decode(file_get_contents('./json/quizzes.json'), true) : [];
$quizId = $_GET['quizId'] ?? null;

$quiz = array_filter($quizzes, fn($quiz) => $quiz['id'] === $quizId);
$quiz = array_values($quiz)[0] ?? null;

if (!$quiz) {
    echo "El cuestionario no existe.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($quiz['title']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="quiz-play" class="animate__animated animate__fadeIn">
        <h1><?= htmlspecialchars($quiz['title']) ?></h1>
        <form action="quizzes.php" method="POST">
            <input type="hidden" name="quizId" value="<?= htmlspecialchars($quiz['id']) ?>">
            <?php foreach ($quiz['questions'] as $index => $question): ?>
                <div class="question-block">
                    <h3><?= htmlspecialchars($question['text']) ?></h3>
                    <?php foreach ($question['answers'] as $answer): ?>
                        <label>
                            <input type="radio" name="answers[<?= $index ?>]" value="<?= htmlspecialchars($answer) ?>" required>
                            <?= htmlspecialchars($answer) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit">Enviar Respuestas</button>
        </form>
        <a href="quizzes.php">Volver a Cuestionarios</a>
    </div>
</body>
</html>
