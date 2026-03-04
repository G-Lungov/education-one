<table>
    <tr>
        <th>Name</th>
        <th>Birth Date</th>
        <th>Attendance</th>
    </tr>

    <?php foreach ($students as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= $student['birth_date'] ?></td>
            <td></td>
        </tr>
    <?php endforeach; ?>
</table>