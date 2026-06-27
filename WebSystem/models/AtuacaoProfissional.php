<?php

namespace App\Models;

class AtuacaoProfissional {
    public static function getByPesquisador(string $id_lattes): array {
        $repo = new \App\Repositories\AtuacaoProfissionalRepository();
        return $repo->getByPesquisador($id_lattes);
    }

    public static function create(array $data): void {
        $repo = new \App\Repositories\AtuacaoProfissionalRepository();
        $repo->create($data);
    }

    public static function update(int $id, array $data): void {
        $repo = new \App\Repositories\AtuacaoProfissionalRepository();
        $repo->update($id, $data);
    }

    public static function delete(int $id): void {
        $repo = new \App\Repositories\AtuacaoProfissionalRepository();
        $repo->delete($id);
    }
}
