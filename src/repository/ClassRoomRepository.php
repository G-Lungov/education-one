<?php

    class ClassRoomRepository
    {
        private PDO $connection;

        public function __construct(PDO $connection)
        {
            $this->connection = $connection;
        }

        public function save(string $description, int $year, int $places): void
        {
            $stmt = $this->connection->prepare(
                "INSERT INTO classrooms (description, year, places) VALUES (?, ?, ?)"
            );

            $stmt->execute([$description, $year, $places]);
        }

        public function findById(int $id): array|false
        {
            $stmt = $this->connection->prepare(
                "SELECT * FROM classrooms WHERE id = ?"
            );

            $stmt->execute([$id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

?>