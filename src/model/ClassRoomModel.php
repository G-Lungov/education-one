<?php

class ClassRoomModel
{
    private int $id;
    private string $description;
    private int $year;
    private int $places;

    public function __construct (string $description, int $year, int $places)
    {
        if ($places <= 0) {
            throw new InvalidArgumentException("Número de vagas deve ser maior que zero, indisponível.");
        }
        $this->description = $description;
        $this->year = $year;
        $this->places = $places;
    }

    public function hasPlaces(int $currentEnrollments): bool
    {
        return $currentEnrollments < $this->places;
    }

    public function getPlaces(): int { return $this->places; }
}

?>