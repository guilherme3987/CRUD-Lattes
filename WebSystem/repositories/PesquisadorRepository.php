<?php

namespace App\Repositories;

use App\Config\Database;

class PesquisadorRepository {
    public function getAll(): array {
        $conn = Database::getConnection();
        $stmt = $conn->query("SELECT * FROM pesquisador ORDER BY nome_completo");
        return $stmt->fetchAll();
    }

    public function getById(string $idLattes): ?array {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM pesquisador WHERE id_lattes = ?");
        $stmt->execute([$idLattes]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): void {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            INSERT INTO pesquisador (id_lattes, email, senha, nome_completo, pais_nascimento, cidade_nascimento, orcid_id, resumo_cv)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['id_lattes'],
            $data['email'] ?? null,
            $data['senha'],
            $data['nome_completo'],
            $data['pais_nascimento'] ?? null,
            $data['cidade_nascimento'] ?? null,
            $data['orcid_id'] ?? null,
            $data['resumo_cv'] ?? null,
        ]);
    }

    public function update(string $idLattes, array $data): void {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("
            UPDATE pesquisador SET
                nome_completo = ?, email = ?,
                pais_nascimento = ?, cidade_nascimento = ?,
                resumo_cv = ?
            WHERE id_lattes = ?
        ");
        $stmt->execute([
            $data['nome_completo'],
            $data['email'] ?? null,
            $data['pais_nascimento'] ?? null,
            $data['cidade_nascimento'] ?? null,
            $data['resumo_cv'] ?? null,
            $idLattes,
        ]);
    }

    public function delete(string $idLattes): void {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM pesquisador WHERE id_lattes = ?");
        $stmt->execute([$idLattes]);
    }

    public function getCount(): int {
        $conn = Database::getConnection();
        return (int) $conn->query("SELECT COUNT(*) FROM pesquisador")->fetchColumn();
    }

    public function findByEmail(string $email): ?array {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM pesquisador WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function searchByOrcid(string $q): array {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM pesquisador WHERE orcid_id LIKE ? ORDER BY nome_completo");
        $stmt->execute(["%$q%"]);
        return $stmt->fetchAll();
    }

    public function searchById(string $q): array {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM pesquisador WHERE id_lattes LIKE ? ORDER BY nome_completo");
        $stmt->execute(["%$q%"]);
        return $stmt->fetchAll();
    }

    public function searchByIdOrOrcid(string $q): array {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM pesquisador WHERE id_lattes LIKE ? OR orcid_id LIKE ? ORDER BY nome_completo");
        $like = "%$q%";
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }
}
