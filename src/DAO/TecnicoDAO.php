<?php
namespace App\DAO;

use App\Config\Database;
use App\Model\Tecnico;

class TecnicoDAO
{
    public function emailExiste(string $email): bool
    {
        $stmt = Database::getConnection()
            ->prepare("SELECT 1 FROM tecnico WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetchColumn();
    }

    public function inserir(Tecnico $t): int
    {
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                "INSERT INTO tecnico (nome, endereco, telefone1, telefone2, email)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $t->nome, $t->endereco, $t->telefone1, $t->telefone2, $t->email
            ]);
            $id = (int) $pdo->lastInsertId();

            $rel = $pdo->prepare(
                "INSERT INTO tecnico_habilidade (tecnico_id, habilidade_id) VALUES (?, ?)"
            );
            foreach ($t->habilidades as $habId) {
                $rel->execute([$id, $habId]);
            }

            $pdo->commit();
            return $id;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }


public function buscar(string $termo, int $limite = 50): array
{
    // escapa curingas do LIKE para o usuário não digitar % ou _
    $termo = addcslashes($termo, '%_\\');
    $like  = '%' . $termo . '%';

    $sql = "
        SELECT t.id, t.nome, t.endereco, t.telefone1, t.telefone2, t.email,
               GROUP_CONCAT(h.nome ORDER BY h.nome SEPARATOR ', ') AS habilidades
        FROM tecnico t
        LEFT JOIN tecnico_habilidade th ON th.tecnico_id = t.id
        LEFT JOIN habilidade h          ON h.id = th.habilidade_id
        WHERE t.nome      LIKE :q1
           OR t.email     LIKE :q2
           OR t.telefone1 LIKE :q3
           OR t.telefone2 LIKE :q4
           OR EXISTS (
                SELECT 1
                FROM tecnico_habilidade th2
                JOIN habilidade h2 ON h2.id = th2.habilidade_id
                WHERE th2.tecnico_id = t.id AND h2.nome LIKE :q5
           )
        GROUP BY t.id
        ORDER BY t.nome
        LIMIT :lim
    ";

    $stmt = Database::getConnection()->prepare($sql);
    foreach (['q1', 'q2', 'q3', 'q4', 'q5'] as $p) {
        $stmt->bindValue(":$p", $like);
    }
    $stmt->bindValue(':lim', $limite, \PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

}