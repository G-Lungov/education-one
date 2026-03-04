<?php

$pdo = require '../config/database.php';

require '../src/Service/StudentService.php';
require '../src/views/layout/Header.php';

$service = new StudentService($pdo);

$message = "";
$editingStudent = null;

// DELETE
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $message = $service->delete($id);

    header("Location: students.php?message=" . urlencode($message));
    exit;
}

// EDIT
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $editingStudent = $service->findById($id);
}

// CREATE or UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $birthDate = $_POST['birth_date'];
    $cpf = $_POST['cpf'];

    if ($id) {
        $message = $service->update((int)$id, $name);
    } else {
        $message = $service->create($name, $birthDate, $cpf);
    }

    header("Location: students.php?message=" . urlencode($message));
    exit;
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

$students = $service->findAll();

?>

<h1>Cadastro de Alunos</h1>

<?php if ($message): ?>
    <p><strong><?= htmlspecialchars($message) ?></strong></p>
<?php endif; ?>

<form method="POST">

    <?php if ($editingStudent): ?>
        <input type="hidden" name="id" value="<?= $editingStudent['id'] ?>">
    <?php endif; ?>

    <label>Nome:</label><br>
    <input type="text" name="name"
        value="<?= $editingStudent ? htmlspecialchars($editingStudent['name']) : '' ?>"
        required><br><br>

    <label>Data de Nascimento:</label><br>
    <input type="date" name="birth_date"
        value="<?= $editingStudent ? $editingStudent['birth_date'] : '' ?>"
        required><br><br>

    <label>CPF:</label><br>
    <input type="text" name="cpf"
        value="<?= $editingStudent ? htmlspecialchars($editingStudent['cpf']) : '' ?>"
        required><br><br>

    <button type="submit">
        <?= $editingStudent ? 'Atualizar' : 'Cadastrar' ?>
    </button>

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
        <th>Ações</th>
    </tr>

    <?php foreach ($students as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= $student['birth_date'] ?></td>
            <td><?= htmlspecialchars($student['cpf']) ?></td>
            <td>
                <a href="students.php?edit=<?= $student['id'] ?>">Editar</a> |
                <a href="students.php?delete=<?= $student['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require '../src/views/layout/Footer.php'; ?>