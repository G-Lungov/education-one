<?php

$pdo = require '../config/database.php';

require '../src/Service/ClassRoomService.php';
require '../src/views/layout/Header.php';

$service = new ClassRoomService($pdo);

$message = "";
$editingClassroom = null;

// DELETE
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $message = $service->delete($id);

    header("Location: classrooms.php?message=" . urlencode($message));
    exit;
}

// EDIT
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $editingClassroom = $service->findById($id);
}

// CREATE or UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? null;
    $description = $_POST['description'];
    $year = (int) $_POST['year'];
    $places = (int) $_POST['places'];

    if ($id) {
        $message = $service->update((int)$id, $description, $year, $places);
    } else {
        $message = $service->create($description, $year, $places);
    }

    header("Location: classrooms.php?message=" . urlencode($message));
    exit;
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

$classrooms = $service->findAll();

?>

<h1>Cadastro de Turmas</h1>

<?php if ($message): ?>
    <p><strong><?= htmlspecialchars($message) ?></strong></p>
<?php endif; ?>

<form method="POST">

    <?php if ($editingClassroom): ?>
        <input type="hidden" name="id" value="<?= $editingClassroom['id'] ?>">
    <?php endif; ?>

    <label>Descrição:</label><br>
    <input type="text" name="description"
        value="<?= $editingClassroom ? htmlspecialchars($editingClassroom['description']) : '' ?>"
        required><br><br>

    <label>Ano:</label><br>
    <input type="number" name="year"
        value="<?= $editingClassroom ? $editingClassroom['year'] : '' ?>"
        required><br><br>

    <label>Vagas:</label><br>
    <input type="number" name="places"
        value="<?= $editingClassroom ? $editingClassroom['places'] : '' ?>"
        required><br><br>

    <button type="submit">
        <?= $editingClassroom ? 'Atualizar' : 'Cadastrar' ?>
    </button>

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
        <th>Ações</th>
    </tr>

    <?php foreach ($classrooms as $classroom): ?>
        <tr>
            <td><?= htmlspecialchars($classroom['description']) ?></td>
            <td><?= $classroom['year'] ?></td>
            <td><?= $classroom['places'] ?></td>
            <td>
                <a href="classrooms.php?edit=<?= $classroom['id'] ?>">Editar</a> |
                <a href="classrooms.php?delete=<?= $classroom['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require '../src/views/layout/Footer.php'; ?>