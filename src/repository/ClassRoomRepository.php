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

    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM classrooms ORDER BY year DESC, description");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM classrooms WHERE id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(int $id, string $description, int $year, int $places): bool
    {
        $stmt = $this->connection->prepare(
            "UPDATE classrooms SET description = ?, year = ?, places = ? WHERE id = ?"
        );

        return $stmt->execute([$description, $year, $places, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM classrooms WHERE id = ?"
        );

        return $stmt->execute([$id]);
    }

    public function hasEnrollments(int $id): bool
    {
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) FROM enrollments WHERE classroom_id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetchColumn() > 0;
    }
}

?>
