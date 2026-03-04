<?php

$pdo = require '../config/database.php';
require '../src/repository/StudentRepository.php';
require '../src/views/layout/Header.php';

$studentRepository = new StudentRepository($pdo);

// Se enviou formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $birthDate = $_POST['birth_date'];
    $cpf = $_POST['cpf'];

    $studentRepository->save($name, $birthDate, $cpf);

    header("Location: students.php");
    exit;
}

$students = $studentRepository->findAll();

?>

<!-- HTML -->

<h1>Cadastro de Alunos</h1>

<form method="POST">
    <label>Nome:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Data de Nascimento:</label><br>
    <input type="date" name="birth_date" required><br><br>

    <label>CPF:</label><br>
    <input type="text" name="cpf" required><br><br>

    <button type="submit">Cadastrar</button>
</form>

<hr>

<h2>Lista de Alunos</h2>

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
        <th>Data Nascimento</th>
        <th>CPF</th>
    </tr>

    <?php foreach ($students as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= $student['birth_date'] ?></td>
            <td><?= $student['cpf'] ?></td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require '../src/views/layout/Footer.php'; ?>
