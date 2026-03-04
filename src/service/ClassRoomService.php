<?php

require_once __DIR__ . '/../Repository/ClassRoomRepository.php';

class ClassRoomService
{
    private ClassRoomRepository $repository;

    public function __construct(PDO $connection)
    {
        $this->repository = new ClassRoomRepository($connection);
    }

    public function create(string $description, int $year, int $places): string
    {
        if (empty($description) || empty($year) || empty($places)) {
            return "Todos os campos são obrigatórios.";
        }

        if ($places <= 0) {
            return "Quantidade de vagas deve ser maior que zero.";
        }

        $this->repository->save($description, $year, $places);

        return "Turma cadastrada com sucesso.";
    }

    public function update(int $id, string $description, int $year, int $places): string
    {
        $classroom = $this->repository->findById($id);

        if (!$classroom) {
            return "Turma não encontrada.";
        }

        if ($places <= 0) {
            return "Quantidade de vagas deve ser maior que zero.";
        }

        $this->repository->update($id, $description, $year, $places);

        return "Turma atualizada com sucesso.";
    }

    public function delete(int $id): string
    {
        $classroom = $this->repository->findById($id);

        if (!$classroom) {
            return "Turma não encontrada.";
        }

        if ($this->repository->hasEnrollments($id)) {
            return "Não é possível excluir turma com alunos matriculados.";
        }

        $this->repository->delete($id);

        return "Turma excluída com sucesso.";
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->repository->findById($id);
    }
}

?>
