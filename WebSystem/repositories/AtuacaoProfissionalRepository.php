<?php

namespace App\Repositories;

use App\Config\Database;

class AtuacaoProfissionalRepository {
    public function getByPesquisador(string $idLattes): array {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM atuacao_profissional WHERE id_lattes = ? ORDER BY ano_inicio DESC");
        $stmt->execute([$idLattes]);
        return $stmt->fetchAll();
    }

    public function create(array $data): void {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO atuacao_profissional (id_lattes, instituicao, ano_inicio, ano_fim, tipo_vinculo, enquadramento_funcional)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['id_lattes'],
            $data['instituicao'],
            $data['ano_inicio'],
            $data['ano_fim'],
            $data['tipo_vinculo'] ?? null,
            $data['enquadramento_funcional'] ?? null,
        ]);
    }

    public function update(int $id, array $data): void {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE atuacao_profissional SET
                instituicao = ?, ano_inicio = ?, ano_fim = ?,
                tipo_vinculo = ?, enquadramento_funcional = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['instituicao'],
            $data['ano_inicio'],
            $data['ano_fim'],
            $data['tipo_vinculo'] ?? null,
            $data['enquadramento_funcional'] ?? null,
            $id,
        ]);
    }

    public function delete(int $id): void {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM atuacao_profissional WHERE id = ?");
        $stmt->execute([$id]);
    }
}
