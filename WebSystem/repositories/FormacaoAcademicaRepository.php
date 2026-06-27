<?php

namespace App\Repositories;

use App\Config\Database;

class FormacaoAcademicaRepository {
    public function getByPesquisador(string $idLattes): array {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM formacao_academica WHERE id_lattes = ? ORDER BY ano_conclusao DESC");
        $stmt->execute([$idLattes]);
        return $stmt->fetchAll();
    }

    public function create(array $data): void {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO formacao_academica (id_lattes, nivel, instituicao, curso, status, ano_inicio, ano_conclusao)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['id_lattes'],
            $data['nivel'],
            $data['instituicao'],
            $data['curso'] ?? null,
            $data['status'] ?? 'CONCLUIDO',
            $data['ano_inicio'],
            $data['ano_conclusao'],
        ]);
    }

    public function update(int $id, array $data): void {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE formacao_academica SET
                nivel = ?, instituicao = ?, curso = ?,
                status = ?, ano_inicio = ?, ano_conclusao = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['nivel'],
            $data['instituicao'],
            $data['curso'] ?? null,
            $data['status'] ?? 'CONCLUIDO',
            $data['ano_inicio'],
            $data['ano_conclusao'],
            $id,
        ]);
    }

    public function delete(int $id): void {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM formacao_academica WHERE id = ?");
        $stmt->execute([$id]);
    }
}
