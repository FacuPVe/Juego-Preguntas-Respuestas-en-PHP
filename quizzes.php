<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$quizzes = file_exists('./json/quizzes.json') ? json_decode(file_get_contents('./json/quizzes.json'), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quizId = $_POST['quizId'];
    $selectedAnswers = $_POST['answers'];

    $quiz = array_filter($quizzes, fn($quiz) => $quiz['id'] === $quizId);
    $quiz = array_values($quiz)[0] ?? null;

    if (!$quiz) {
        echo "El cuestionario no existe.";
        exit;
    }

    $correct = 0;
    foreach ($quiz['questions'] as $index => $question) {
        if ($question['correctAnswer'] === ($selectedAnswers[$index] ?? '')) {
            $correct++;
        }
    }

    $resultados = file_exists('./json/resultados.json') ? json_decode(file_get_contents('./json/resultados.json'), true) : [];
    $resultados[] = [
        'userId' => $_SESSION['userId'],
        'quizId' => $quizId,
        'title' => $quiz['title'],
        'correctas' => $correct,
        'incorrectas' => count($quiz['questions']) - $correct,
        'fecha' => date('Y-m-d H:i:s')
    ];
    file_put_contents('./json/resultados.json', json_encode($resultados, JSON_PRETTY_PRINT));

    header("Location: results.php?quizId=$quizId&correctas=$correct&total=" . count($quiz['questions']));
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="quizzes" class="animate__animated animate__fadeIn">
        <h1>Cuestionarios Disponibles</h1>
        <div class="quiz-list">
            <?php if (empty($quizzes)): ?>
                <p>No hay cuestionarios disponibles.</p>
            <?php else: ?>
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="quiz-card">
                        <h2><?= htmlspecialchars($quiz['title'] ?? 'Sin título') ?></h2>
                        <p>Creador: <?= htmlspecialchars($quiz['creator'] ?? 'Anónimo') ?></p>
                        <form action="play_quiz.php" method="GET">
                            <input type="hidden" name="quizId" value="<?= htmlspecialchars($quiz['id']) ?>">
                            <button type="submit">Hacer Quizz</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a href="dashboard.php">Volver al Dashboard</a>
    </div>
</body>
</html>
