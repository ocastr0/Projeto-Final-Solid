<?php
namespace App\Domain\Interface;

interface EstacionamentoRepository
{
    public function registrarEntrada(string $placa, string $dataHora): void;
    public function registrarSaida(string $placa, string $dataHora, float $valor): void;
    public function buscarAtivo(string $placa): ?array;
    public function listarFinalizados(): array;
}



