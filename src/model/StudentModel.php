<?php

class StudentModel
{
    private int $id;
    private string $name;
    private DateTime $birthDate;
    private string $cpf;

    public function __construct(string $name, DateTime $birthDate, string $cpf)
    {
        $this->validateCPF($cpf);
        $this->name = $name;
        $this->birthDate = $birthDate;
        $this->cpf = $cpf;
    }

    private function validateCPF(string $cpf): void
    {
        if (strlen($cpf) !== 11) {
            throw new InvalidArgumentException("CPF inválido");
        }
    }

    public function getName(): string { return $this->name; }
    public function getBirthDate(): DateTime { return $this->birthDate; }
    public function getCPF(): string { return $this->cpf; }
}

?>