<?php

class EnrollmentService
{
    private EnrollmentRepository $enrollmentRepository;
    private ClassRoomRepository $classRoomRepository;
    private PDO $connection;

    public function __construct(EnrollmentRepository $enrollmentRepository, ClassRoomRepository $classRoomRepository, PDO $connection)
    {
        $this->enrollmentRepository = $enrollmentRepository;
        $this->classRoomRepository = $classRoomRepository;
        $this->connection = $connection;
    }

    public function enroll(int $studentId, int $classRoomId): void
    {
        $this->connection->beginTransaction();
        try {
            $classroom = $this->classRoomRepository->findByIdForUpdate($classRoomId);
            $currentEnrollments = $this->enrollmentRepository->countByClassRoomId($classRoomId);

            if (!$classroom->hasPlaces($currentEnrollments)) {
                throw new Exception("Turma cheia, não é possível realizar a matrícula.");
            }

            $this->enrollmentRepository->save($studentId, $classRoomId);
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}

?>