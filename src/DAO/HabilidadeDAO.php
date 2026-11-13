<?php
namespace App\DAO;

use App\Config\Database;

class HabilidadeDAO
{
    public function listarTodas(): array
    {
        $pdo = Database::getConnection();
        return $pdo->query("SELECT id, nome FROM habilidade ORDER BY nome")
                   ->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function idsValidos(array $ids): array
    {
        if (!$ids) return [];
        $in  = implode(',', array_fill(0, count($ids), '?'));
        $stmt = Database::getConnection()
            ->prepare("SELECT id FROM habilidade WHERE id IN ($in)");
        $stmt->execute(array_values($ids));
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}