<?php
require '../src/views/layout/Header.php';
?>

<!-- HTML -->

    <h1>Education One</h1>

    <p>Selecione uma opção:</p>

    <form action="Students.php" method="get">
        <button type="submit">Alunos</button>
    </form>

    <form action="ClassRooms.php" method="get">
        <button type="submit">Turmas</button>
    </form>

    <form action="Enrollments.php" method="get">
        <button type="submit">Matrículas</button>
    </form>

    <form action="Report.php" method="get">
        <button type="submit">Relatórios</button>
    </form>

<?php require '../src/views/layout/Footer.php'; ?>
