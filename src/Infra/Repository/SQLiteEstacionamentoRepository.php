<?php
namespace App\Infra\Repository;

use App\Domain\Interface\EstacionamentoRepository;
use PDO;

class SQLiteEstacionamentoRepository implements EstacionamentoRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->createTable();
    }

    private function createTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS estacionamentos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                placa TEXT NOT NULL,
                entrada TEXT NOT NULL,
                saida TEXT,
                valor REAL,
                FOREIGN KEY(placa) REFERENCES veiculos(placa)
            )
        ");
    }

    public function registrarEntrada(string $placa, string $dataHora): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO estacionamentos (placa, entrada)
            VALUES (:placa, :entrada)
        ");
        $stmt->execute([
            ':placa' => $placa,
            ':entrada' => $dataHora
        ]);
    }

    public function registrarSaida(string $placa, string $dataHora, float $valor): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE estacionamentos
            SET saida = :saida, valor = :valor
            WHERE placa = :placa AND saida IS NULL
        ");
        $stmt->execute([
            ':saida' => $dataHora,
            ':valor' => $valor,
            ':placa' => $placa
        ]);
    }

    public function buscarAtivo(string $placa): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM estacionamentos
            WHERE placa = :placa AND saida IS NULL
            LIMIT 1
        ");
        $stmt->execute([':placa' => $placa]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function listarFinalizados(): array
    {
        $stmt = $this->pdo->query("
            SELECT e.placa, v.tipo, e.entrada, e.saida, e.valor
            FROM estacionamentos e
            INNER JOIN veiculos v ON e.placa = v.placa
            WHERE e.saida IS NOT NULL
            ORDER BY e.saida DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


