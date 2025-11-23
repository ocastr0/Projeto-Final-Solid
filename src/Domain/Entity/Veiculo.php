<?php
namespace App\Domain\Entity;

abstract class Veiculo
{
    protected string $placa;
    protected string $tipo;

    public function __construct(string $placa)
    {
        $this->placa = $placa;
    }

    public function getPlaca(): string
    {
        return $this->placa;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }
}
