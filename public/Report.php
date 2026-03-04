<?php

$pdo = require '../config/database.php';
require '../src/Service/AttendanceReportService.php';
require '../src/views/layout/Header.php';

// Buscar turmas
$stmt = $pdo->query("SELECT id, description, year FROM classrooms");
$classrooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

$students = [];

if (isset($_GET['classroom_id']) && !empty($_GET['classroom_id'])) {

    $service = new AttendanceReportService($pdo);
    $students = $service->generateByClassRoom((int) $_GET['classroom_id']);
}

?>

<!-- HTML -->

<h1>Relatório de Chamada</h1>

<form method="GET">
    <label>Selecione a Turma:</label>
    <select name="classroom_id">
        <option value="">-- Escolha --</option>
        <?php foreach ($classrooms as $classroom): ?>
            <option value="<?= $classroom['id'] ?>">
                <?= $classroom['description'] ?> - <?= $classroom['year'] ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Gerar</button>
</form>

<?php if (!empty($students)): ?>

    <h2>Lista de Chamada</h2>

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
            <th>Nome</th>
            <th>Data de Nascimento</th>
            <th>Chamada</th>
        </tr>

        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['name']) ?></td>
                <td><?= $student['birth_date'] ?></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>

<?php require '../src/views/layout/Footer.php'; ?>
