<?php

    class StudentRepository
    {
        private PDO $connection;

        public function __construct(PDO $connection)
        {
            $this->connection = $connection;
        }

        public function save(string $name, string $birthDate, string $cpf): void
        {
            $stmt = $this->connection->prepare(
                "INSERT INTO students (name, birth_date, cpf) VALUES (?, ?, ?)"
            );

            $stmt->execute([$name, $birthDate, $cpf]);
        }

        public function findAll(): array
        {
            $stmt = $this->connection->query("SELECT * FROM students");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function findById($id)
        {
            $stmt = $this->connection->prepare("SELECT * FROM students WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function update($id, $name)
        {
            $stmt = $this->connection->prepare("UPDATE students SET name = ? WHERE id = ?");
            return $stmt->execute([$name, $id]);
        }

        public function delete($id)
        {
            $stmt = $this->connection->prepare("DELETE FROM students WHERE id = ?");
            return $stmt->execute([$id]);
        }

        public function hasEnrollments($id)
        {
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id = ?");
            $stmt->execute([$id]);
            return $stmt->fetchColumn() > 0;
        }
    }

?>