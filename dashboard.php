<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$resultados = file_exists('./json/resultados.json') ? json_decode(file_get_contents('./json/resultados.json'), true) : [];
$resultadosUsuario = array_filter($resultados, fn($res) => $res['userId'] === $_SESSION['userId']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div id="dashboard" class="animate__animated animate__fadeIn">
        <?php
        if (isset($_SESSION['flash_message'])) {
            echo '<div id="flash-message-data" data-message="' . htmlspecialchars($_SESSION['flash_message']) . '"></div>';
            unset($_SESSION['flash_message']);
        }
        ?>
        <h1>Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?></h1>
        <nav>
            <a href="create_quiz.php">Crear Cuestionario</a> |
            <a href="quizzes.php">Ver Cuestionarios</a> |
            <a href="index.php" onclick="document.getElementById('logout-form').submit(); return false;">Cerrar
                Sesión</a>
        </nav>
        <form id="logout-form" action="index.php" method="POST" style="display: none;">
            <input type="hidden" name="action" value="logout">
        </form>

        <h2>Tus Resultados</h2>
        <table>
            <thead>
                <tr>
                    <th>Cuestionario</th>
                    <th>Respuestas correctas</th>
                    <th>Respuestas incorrectas</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultadosUsuario as $resultado): ?>
                    <tr>
                        <td><?= htmlspecialchars($resultado['title']) ?></td>
                        <td><?= $resultado['correctas'] ?></td>
                        <td><?= $resultado['incorrectas'] ?></td>
                        <td><?= htmlspecialchars($resultado['fecha']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="flashModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>