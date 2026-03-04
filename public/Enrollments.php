<?php

$pdo = require '../config/database.php';
require '../src/service/EnrollmentService.php';
require '../src/views/layout/Header.php';

$enrollmentRepository = new EnrollmentRepository($pdo);

// Buscar alunos
$studentsStmt = $pdo->query("SELECT id, name FROM students ORDER BY name");
$students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar turmas
$classroomsStmt = $pdo->query("SELECT id, description, year, places FROM classrooms");
$classrooms = $classroomsStmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $studentId = (int) $_POST['student_id'];
    $classRoomId = (int) $_POST['classroom_id'];

    $service = new EnrollmentService($pdo);
    $message = $service->enroll($studentId, $classRoomId);
}

// Listar matrículas
$listStmt = $pdo->query("
    SELECT s.name, c.description, c.year
    FROM enrollments e
    JOIN students s ON s.id = e.student_id
    JOIN classrooms c ON c.id = e.classroom_id
    ORDER BY c.year, c.description
");

$enrollments = $listStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- HTML -->

<h1>Matrículas</h1>

<?php if ($message): ?>
    <p><strong><?= $message ?></strong></p>
<?php endif; ?>

<form method="POST">

    <label>Aluno:</label><br>
    <select name="student_id" required>
        <option value="">-- Escolha --</option>
        <?php foreach ($students as $student): ?>
            <option value="<?= $student['id'] ?>">
                <?= htmlspecialchars($student['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Turma:</label><br>
    <select name="classroom_id" required>
        <option value="">-- Escolha --</option>
        <?php foreach ($classrooms as $classroom): ?>
            <option value="<?= $classroom['id'] ?>">
                <?= $classroom['description'] ?> - <?= $classroom['year'] ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Matricular</button>

</form>

<hr>

<h2>Lista de Matrículas</h2>

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
        <th>Aluno</th>
        <th>Turma</th>
    </tr>

    <?php foreach ($enrollments as $enrollment): ?>
        <tr>
            <td><?= htmlspecialchars($enrollment['name']) ?></td>
            <td><?= $enrollment['description'] ?> - <?= $enrollment['year'] ?></td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require '../src/views/layout/Footer.php'; ?>
