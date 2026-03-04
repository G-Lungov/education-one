<?php

require_once __DIR__ . '/../Repository/EnrollmentRepository.php';
require_once __DIR__ . '/../Repository/ClassRoomRepository.php';
require_once __DIR__ . '/../Repository/StudentRepository.php';

class EnrollmentService
{
    private EnrollmentRepository $enrollmentRepository;
    private ClassRoomRepository $classRoomRepository;
    private StudentRepository $studentRepository;
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->enrollmentRepository = new EnrollmentRepository($connection);
        $this->classRoomRepository = new ClassRoomRepository($connection);
        $this->studentRepository = new StudentRepository($connection);
    }

    // =========================
    // NOVA MATRÍCULA
    // =========================
    public function enroll(int $studentId, int $classRoomId): string
    {
        try {

            $this->connection->beginTransaction();

            if (!$this->studentRepository->findById($studentId)) {
                throw new Exception("Aluno não encontrado.");
            }

            $classroom = $this->classRoomRepository->findById($classRoomId);
            if (!$classroom) {
                throw new Exception("Turma não encontrada.");
            }

            if ($this->enrollmentRepository->findByStudent($studentId)) {
                throw new Exception("Aluno já possui matrícula ativa.");
            }

            $currentCount = $this->enrollmentRepository->countByClassRoom($classRoomId);

            if ($currentCount >= $classroom['places']) {
                throw new Exception("Turma sem vagas.");
            }

            $this->enrollmentRepository->save(
                $studentId,
                $classRoomId,
                date('Y-m-d')
            );

            $this->connection->commit();
            return "Matrícula realizada com sucesso!";

        } catch (Exception $e) {

            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            return $e->getMessage();
        }
    }

    // =========================
    // TROCAR DE TURMA
    // =========================
    public function transfer(int $enrollmentId, int $newClassRoomId): string
    {
        try {

            $this->connection->beginTransaction();

            $enrollment = $this->enrollmentRepository->findById($enrollmentId);
            if (!$enrollment) {
                throw new Exception("Matrícula não encontrada.");
            }

            if ($enrollment['classroom_id'] == $newClassRoomId) {
                throw new Exception("Aluno já está nesta turma.");
            }

            $classroom = $this->classRoomRepository->findById($newClassRoomId);
            if (!$classroom) {
                throw new Exception("Turma não encontrada.");
            }

            $currentCount = $this->enrollmentRepository->countByClassRoom($newClassRoomId);

            if ($currentCount >= $classroom['places']) {
                throw new Exception("Turma sem vagas.");
            }

            $this->enrollmentRepository->updateClassroom(
                $enrollmentId,
                $newClassRoomId
            );

            $this->connection->commit();
            return "Turma alterada com sucesso.";

        } catch (Exception $e) {

            if ($this->connection->inTransaction()) {
                $this->connection->rollBack();
            }

            return $e->getMessage();
        }
    }

    // =========================
    // REMOVER MATRÍCULA
    // =========================
    public function delete(int $id): string
    {
        if (!$this->enrollmentRepository->findById($id)) {
            return "Matrícula não encontrada.";
        }

        $this->enrollmentRepository->delete($id);

        return "Matrícula removida com sucesso.";
    }
}

?>
