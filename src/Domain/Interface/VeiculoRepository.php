<?php
namespace App\Domain\Interface;

use App\Domain\Entity\Veiculo;

interface VeiculoRepository
{
    public function save(Veiculo $veiculo): void;
    public function findByPlaca(string $placa): ?Veiculo;
    public function listAll(): array;
}






