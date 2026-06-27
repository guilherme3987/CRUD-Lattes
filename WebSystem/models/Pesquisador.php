<?php

namespace App\Models;

use App\Config\Database;

class Pesquisador {
    public static function getAll(): array {
        $repo = new \App\Repositories\PesquisadorRepository();
        return $repo->getAll();
    }

    public static function getById(string $id_lattes): ?array {
        $repo = new \App\Repositories\PesquisadorRepository();
        return $repo->getById($id_lattes);
    }

    public static function create(array $data): void {
        $repo = new \App\Repositories\PesquisadorRepository();
        $repo->create($data);
    }

    public static function update(string $id_lattes, array $data): void {
        $repo = new \App\Repositories\PesquisadorRepository();
        $repo->update($id_lattes, $data);
    }

    public static function delete(string $id_lattes): void {
        $repo = new \App\Repositories\PesquisadorRepository();
        $repo->delete($id_lattes);
    }

    public static function getCount(): int {
        $repo = new \App\Repositories\PesquisadorRepository();
        return $repo->getCount();
    }

    public static function findByEmail(string $email): ?array {
        $repo = new \App\Repositories\PesquisadorRepository();
        return $repo->findByEmail($email);
    }

    public static function searchByOrcid(string $q): array {
        $repo = new \App\Repositories\PesquisadorRepository();
        return $repo->searchByOrcid($q);
    }

    public static function searchById(string $q): array {
        $repo = new \App\Repositories\PesquisadorRepository();
        return $repo->searchById($q);
    }

    public static function searchByIdOrOrcid(string $q): array {
        $repo = new \App\Repositories\PesquisadorRepository();
        return $repo->searchByIdOrOrcid($q);
    }
}
