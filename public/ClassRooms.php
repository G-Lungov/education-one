<?php

$pdo = require '../config/database.php';
require '../src/Repository/ClassRoomRepository.php';
require '../src/views/layout/Header.php';

$classRoomRepository = new ClassRoomRepository($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $year = (int) $_POST['year'];
    $places = (int) $_POST['places'];

    $classRoomRepository->save($description, $year, $places);

    header("Location: classrooms.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM classrooms");
$classrooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- HTML -->

<h1>Cadastro de Turmas</h1>

<form method="POST">
    <label>Descrição:</label><br>
    <input type="text" name="description" required><br><br>

    <label>Ano:</label><br>
    <input type="number" name="year" required><br><br>

    <label>Vagas:</label><br>
    <input type="number" name="places" required><br><br>

    <button type="submit">Cadastrar</button>
</form>

<hr>

<h2>Lista de Turmas</h2>

<style>
    table, th, td {
        border-collapse: collapse;
        border: 1px solid black;
        padding: 5px;
        width: 100%;
    }
</style>
<table>
    <tr>
        <th>Descrição</th>
        <th>Ano</th>
        <th>Vagas</th>
    </tr>

    <?php foreach ($classrooms as $classroom): ?>
        <tr>
            <td><?= htmlspecialchars($classroom['description']) ?></td>
            <td><?= $classroom['year'] ?></td>
            <td><?= $classroom['places'] ?></td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require '../src/views/layout/Footer.php'; ?>
