<?php

require_once __DIR__ . '/../Repository/EnrollmentRepository.php';

class EnrollmentService
{
    private EnrollmentRepository $enrollmentRepository;
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->enrollmentRepository = new EnrollmentRepository($connection);
    }

    public function enroll(int $studentId, int $classRoomId): string
    {
        // Inicia transação
        $this->connection->beginTransaction();

        try {

            // Conta matrículas existentes
            $currentCount = $this->enrollmentRepository->countByClassRoom($classRoomId);

            // Busca quantidade de vagas
            $stmt = $this->connection->prepare(
                "SELECT places FROM classrooms WHERE id = ?"
            );
            $stmt->execute([$classRoomId]);
            $places = (int) $stmt->fetchColumn();

            if ($currentCount >= $places) {
                $this->connection->rollBack();
                return "Turma sem vagas.";
            }

            $this->enrollmentRepository->save(
                $studentId,
                $classRoomId,
                date('Y-m-d')
            );

            $this->connection->commit();
            return "Matrícula realizada com sucesso!";

        } catch (Exception $e) {
            $this->connection->rollBack();
            return "Erro ao realizar matrícula.";
        }
    }
}

?>
