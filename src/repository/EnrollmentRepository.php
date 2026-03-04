<?php

class EnrollmentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(int $studentId, int $classRoomId, string $date): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO enrollments (student_id, classroom_id, enrollment_date) VALUES (?, ?, ?)"
        );

        $stmt->execute([$studentId, $classRoomId, $date]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM enrollments WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }

    public function findByStudent(int $studentId): array|false
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM enrollments WHERE student_id = ?"
        );

        $stmt->execute([$studentId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM enrollments WHERE id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateClassroom(int $id, int $classRoomId): bool
    {
        $stmt = $this->connection->prepare(
            "UPDATE enrollments SET classroom_id = ? WHERE id = ?"
        );

        return $stmt->execute([$classRoomId, $id]);
    }

    public function countByClassRoom(int $classRoomId): int
    {
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) FROM enrollments WHERE classroom_id = ?"
        );

        $stmt->execute([$classRoomId]);

        return (int) $stmt->fetchColumn();
    }
}
?>
