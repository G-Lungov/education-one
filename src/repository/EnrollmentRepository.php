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