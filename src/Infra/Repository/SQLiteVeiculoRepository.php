<?php
namespace App\Infra\Repository;

use App\Domain\Entity\Veiculo;
use App\Domain\Entity\Carro;
use App\Domain\Entity\Moto;
use App\Domain\Entity\Caminhao;
use App\Domain\Interface\VeiculoRepository;
use PDO;

class SQLiteVeiculoRepository implements VeiculoRepository
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
            CREATE TABLE IF NOT EXISTS veiculos (
                placa TEXT PRIMARY KEY,
                tipo TEXT NOT NULL
            )
        ");
    }

    public function save(Veiculo $veiculo): void
    {
        $stmt = $this->pdo->prepare("
            INSERT OR REPLACE INTO veiculos (placa, tipo)
            VALUES (:placa, :tipo)
        ");
        $stmt->execute([
            ':placa' => $veiculo->getPlaca(),
            ':tipo' => $veiculo->getTipo()
        ]);
    }

    public function findByPlaca(string $placa): ?Veiculo
    {
        $stmt = $this->pdo->prepare("SELECT * FROM veiculos WHERE placa = :placa");
        $stmt->execute([':placa' => $placa]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function listAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM veiculos");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    private function hydrate(array $row): Veiculo
    {
        return match ($row['tipo']) {
            'carro' => new Carro($row['placa']),
            'moto' => new Moto($row['placa']),
            'caminhao' => new Caminhao($row['placa']),
            default => throw new \Exception("Tipo de veículo desconhecido: {$row['tipo']}")
        };
    }
}


