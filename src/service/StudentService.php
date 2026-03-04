<?php

require_once __DIR__ . '/../Repository/StudentRepository.php';

class StudentService
{
    private StudentRepository $repository;

    public function __construct(PDO $connection)
    {
        $this->repository = new StudentRepository($connection);
    }

    public function create(string $name, string $birthDate, string $cpf): string
    {
        if (empty($name) || empty($birthDate) || empty($cpf)) {
            return "Todos os campos são obrigatórios.";
        }

        $this->repository->save($name, $birthDate, $cpf);

        return "Aluno cadastrado com sucesso.";
    }

    public function update(int $id, string $name): string
    {
        if (empty($name)) {
            return "Nome não pode ser vazio.";
        }

        $student = $this->repository->findById($id);

        if (!$student) {
            return "Aluno não encontrado.";
        }

        $this->repository->update($id, $name);

        return "Aluno atualizado com sucesso.";
    }

    public function delete(int $id): string
    {
        $student = $this->repository->findById($id);

        if (!$student) {
            return "Aluno não encontrado.";
        }

        if ($this->repository->hasEnrollments($id)) {
            return "Não é possível excluir aluno com matrícula ativa.";
        }

        $this->repository->delete($id);

        return "Aluno excluído com sucesso.";
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
