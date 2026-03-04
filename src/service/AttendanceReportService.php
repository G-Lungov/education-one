<?php

class AttendanceReportService
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function generateByClassRoom (int $classRoomId): array
    {
        $stmt = $this->connection->prepare("
            SELECT s.name, s.birth_date
            FROM enrollments e
            JOIN students s ON s.id = e.student_id
            WHERE e.classroom_id = ?
            ORDER BY s.name
        ");

        $stmt->execute([$classRoomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>