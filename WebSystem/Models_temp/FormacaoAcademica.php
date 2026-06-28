<?php

namespace App\Models;

class FormacaoAcademica {
    public static function getByPesquisador(string $id_lattes): array {
        $repo = new \App\Repositories\FormacaoAcademicaRepository();
        return $repo->getByPesquisador($id_lattes);
    }

    public static function create(array $data): void {
        $repo = new \App\Repositories\FormacaoAcademicaRepository();
        $repo->create($data);
    }

    public static function update(int $id, array $data): void {
        $repo = new \App\Repositories\FormacaoAcademicaRepository();
        $repo->update($id, $data);
    }

    public static function delete(int $id): void {
        $repo = new \App\Repositories\FormacaoAcademicaRepository();
        $repo->delete($id);
    }
}
