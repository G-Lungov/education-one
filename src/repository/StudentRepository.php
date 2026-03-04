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
    }

?>