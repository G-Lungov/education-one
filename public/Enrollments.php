<?php

$pdo = require '../config/database.php';

require '../src/Service/EnrollmentService.php';
require '../src/views/layout/Header.php';

$service = new EnrollmentService($pdo);
$studentRepository = new StudentRepository($pdo);
$classRoomRepository = new ClassRoomRepository($pdo);
$enrollmentRepository = new EnrollmentRepository($pdo);

$message = "";
$editingEnrollment = null;

// DELETE
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $message = $service->delete($id);

    header("Location: enrollments.php?message=" . urlencode($message));
    exit;
}

// EDIT (TROCAR DE TURMA)
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $editingEnrollment = $enrollmentRepository->findById($id);
}

// CREATE ou UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $studentId = (int) $_POST['student_id'];
    $classRoomId = (int) $_POST['classroom_id'];

    // Se estiver editando → transferir
    if (isset($_POST['enrollment_id']) && !empty($_POST['enrollment_id'])) {
        $enrollmentId = (int) $_POST['enrollment_id'];
        $message = $service->transfer($enrollmentId, $classRoomId);
    } else {
        // Senão → nova matrícula
        $message = $service->enroll($studentId, $classRoomId);
    }

    header("Location: enrollments.php?message=" . urlencode($message));
    exit;
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

// Buscar alunos
$students = $studentRepository->findAll();

// Buscar turmas
$classrooms = $classRoomRepository->findAll();

// Listar matrículas
$stmt = $pdo->query("
    SELECT
        e.id,
        e.student_id,
        s.name,
        c.description,
        c.year
    FROM enrollments e
    JOIN students s ON s.id = e.student_id
    JOIN classrooms c ON c.id = e.classroom_id
    ORDER BY c.year, c.description
");

$enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Matrículas</h1>

<?php if ($message): ?>
    <p><strong><?= htmlspecialchars($message) ?></strong></p>
<?php endif; ?>

<h2><?= $editingEnrollment ? "Trocar Turma" : "Nova Matrícula" ?></h2>

<form method="POST">

    <?php if ($editingEnrollment): ?>
        <input type="hidden" name="enrollment_id" value="<?= $editingEnrollment['id'] ?>">
    <?php endif; ?>

    <label>Aluno:</label><br>
    <select name="student_id" required <?= $editingEnrollment ? 'disabled' : '' ?>>
        <option value="">-- Escolha --</option>
        <?php foreach ($students as $student): ?>
            <option value="<?= $student['id'] ?>"
                <?= ($editingEnrollment && $editingEnrollment['student_id'] == $student['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($student['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <?php if ($editingEnrollment): ?>
        <input type="hidden" name="student_id" value="<?= $editingEnrollment['student_id'] ?>">
    <?php endif; ?>

    <label>Turma:</label><br>
    <select name="classroom_id" required>
        <option value="">-- Escolha --</option>
        <?php foreach ($classrooms as $classroom): ?>
            <option value="<?= $classroom['id'] ?>">
                <?= htmlspecialchars($classroom['description']) ?> - <?= $classroom['year'] ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">
        <?= $editingEnrollment ? "Trocar Turma" : "Matricular" ?>
    </button>

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
        <th>ID</th>
        <th>Aluno</th>
        <th>Turma</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($enrollments as $enrollment): ?>
        <tr>
            <td><?= $enrollment['id'] ?></td>
            <td><?= htmlspecialchars($enrollment['name']) ?></td>
            <td><?= htmlspecialchars($enrollment['description']) ?> - <?= $enrollment['year'] ?></td>
            <td>
                <a href="enrollments.php?edit=<?= $enrollment['id'] ?>">Trocar</a> |
                <a href="enrollments.php?delete=<?= $enrollment['id'] ?>"onclick="return confirm('Tem certeza que deseja remover a matrícula?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require '../src/views/layout/Footer.php'; ?>
