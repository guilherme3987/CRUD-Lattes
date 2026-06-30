<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Repositories\PesquisadorRepository;
use App\Repositories\FormacaoAcademicaRepository;
use App\Repositories\AtuacaoProfissionalRepository;

class PesquisadorController extends BaseController {
    private PesquisadorRepository $pesquisadorRepo;
    private FormacaoAcademicaRepository $formacaoRepo;
    private AtuacaoProfissionalRepository $atuacaoRepo;

    public function __construct() {
        parent::__construct();
        $this->pesquisadorRepo = new PesquisadorRepository();
        $this->formacaoRepo = new FormacaoAcademicaRepository();
        $this->atuacaoRepo = new AtuacaoProfissionalRepository();
    }

    public function index(): void {
        if ($this->auth->isAuthenticated()) {
            $this->redirect('/?action=profile');
        }
        $this->render('index', [
            'pesquisadores' => $this->pesquisadorRepo->getAll(),
            'total_pesquisadores' => $this->pesquisadorRepo->getCount(),
        ]);
    }

    public function list(): void {
        if ($this->auth->isAuthenticated()) {
            $this->redirect('/?action=profile');
        }
        $this->render('pesquisador/index', [
            'pesquisadores' => $this->pesquisadorRepo->getAll(),
        ]);
    }

    public function profile(): void {
        $this->auth->requireAuth();
        $this->show($this->auth->getLoggedInId());
    }

    public function show(string $id): void {
        $pesquisador = $this->pesquisadorRepo->getById($id);
        if (!$pesquisador) {
            $this->redirect('/');
        }
        if ($this->auth->isAuthenticated() && $this->auth->getLoggedInId() !== $id) {
            $this->redirect('/?action=profile');
        }
        $this->render('pesquisador/show', [
            'pesquisador' => $pesquisador,
            'atuacoes' => $this->atuacaoRepo->getByPesquisador($id),
            'formacoes' => $this->formacaoRepo->getByPesquisador($id),
            'is_owner' => $this->auth->isAuthenticated() && $this->auth->getLoggedInId() === $id,
        ]);
    }

    public function search(): void {
        if ($this->auth->isAuthenticated()) {
            $this->redirect('/?action=profile');
        }
        $q = $_GET['q'] ?? '';
        if ($q !== '') {
            $pesquisadores = $this->pesquisadorRepo->searchByOrcid($q);
            if (count($pesquisadores) === 1) {
                $this->redirect('/?action=show&id=' . urlencode($pesquisadores[0]['id_lattes']));
            }
        }
        $this->redirect('/');
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if (!$this->csrf->validateToken($_POST['_csrf_token'] ?? null)) {
                $this->render('login/login', ['erro' => 'Sessão inválida. Tente novamente.']);
                return;
            }

            $pesquisador = $this->pesquisadorRepo->findByEmail($email);

            if ($pesquisador && $this->auth->verifyPassword($senha, $pesquisador['senha'])) {
                $this->auth->login($pesquisador['id_lattes'], $pesquisador['nome_completo']);
                $this->redirect('/?action=profile');
            }

            $this->render('login/login', ['erro' => 'Credenciais inválidas']);
            return;
        }

        $this->render('login/login');
    }

    public function logout(): void {
        $this->auth->logout();
        $this->redirect('/');
    }

    public function create(): void {
        if ($this->auth->isAuthenticated()) {
            $this->redirect('/?action=profile');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->csrf->validateToken($_POST['_csrf_token'] ?? null)) {
                $this->redirect('/?action=create');
                return;
            }

            $allowed = ['id_lattes', 'email', 'senha', 'nome_completo', 'pais_nascimento', 'cidade_nascimento', 'orcid_id', 'resumo_cv'];
            $data = array_intersect_key($_POST, array_flip($allowed));

            $erros = [];

            $id_lattes = $data['id_lattes'] ?? '';
            if (!preg_match('/^\d{16}$/', $id_lattes)) {
                $erros[] = 'ID Lattes inválido. Deve conter exatamente 16 dígitos.';
            }

            $email = $data['email'] ?? '';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erros[] = 'E-mail inválido.';
            }

            $senha = $data['senha'] ?? '';
            if (strlen($senha) < 6) {
                $erros[] = 'Senha deve ter no mínimo 6 caracteres.';
            }

            $nome = $data['nome_completo'] ?? '';
            if (!preg_match('/^[\p{L}\s.\'\-]+$/u', $nome)) {
                $erros[] = 'Nome completo inválido.';
            }

            $pais = $data['pais_nascimento'] ?? '';
            if (!preg_match('/^[\p{L}\s\'\-]+$/u', $pais)) {
                $erros[] = 'País de nascimento inválido.';
            }

            $cidade = $data['cidade_nascimento'] ?? '';
            if (!preg_match('/^[\p{L}\s\'\-]+$/u', $cidade)) {
                $erros[] = 'Cidade de nascimento inválida.';
            }

            $orcid = $data['orcid_id'] ?? '';
            if ($orcid !== '') {
                $isOrcid = preg_match('/^\d{4}-\d{4}-\d{4}-\d{3}[\dX]$/', $orcid);
                $isOrcidUrl = preg_match('/^https:\/\/orcid\.org\/\d{4}-\d{4}-\d{4}-\d{3}[\dX]$/', $orcid);
                if (!$isOrcid && !$isOrcidUrl) {
                    $erros[] = 'ORCID inválido. Formato: 0000-0000-0000-0000 ou https://orcid.org/0000-0000-0000-0000.';
                }
            }

            if (empty($erros)) {
                if ($this->pesquisadorRepo->getById($id_lattes)) {
                    $erros[] = 'ID Lattes já cadastrado.';
                }
                if ($this->pesquisadorRepo->findByEmail($email)) {
                    $erros[] = 'E-mail já cadastrado.';
                }
            }

            if (!empty($erros)) {
                $this->render('pesquisador/create', ['erro' => implode("\n", $erros)]);
                return;
            }

            $data['senha'] = $this->auth->hashPassword($senha);
            $this->pesquisadorRepo->create($data);
            $this->auth->login($id_lattes, $nome);
            $this->redirect('/?action=edit');
        }
        $this->render('pesquisador/create');
    }

    public function edit(): void {
        $this->auth->requireAuth();
        $id = $this->auth->getLoggedInId();
        $pesquisador = $this->pesquisadorRepo->getById($id);
        if (!$pesquisador) {
            $this->redirect('/');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->csrf->validateToken($_POST['_csrf_token'] ?? null)) {
                $this->redirect('/?action=edit');
                return;
            }
            $this->pesquisadorRepo->update($id, $_POST);
            $this->redirect('/?action=profile');
        }
        $this->render('pesquisador/edit', [
            'pesquisador' => $pesquisador,
            'atuacoes' => $this->atuacaoRepo->getByPesquisador($id),
            'formacoes' => $this->formacaoRepo->getByPesquisador($id),
        ]);
    }

    public function delete(): void {
        $this->auth->requireAuth();
        $this->pesquisadorRepo->delete($this->auth->getLoggedInId());
        $this->auth->logout();
        $this->redirect('/');
    }

    public function addAtuacao(): void {
        $this->auth->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->csrf->validateToken($_POST['_csrf_token'] ?? null)) {
                $this->redirect('/?action=edit');
                return;
            }
            $data = $_POST;
            $data['id_lattes'] = $this->auth->getLoggedInId();
            $this->atuacaoRepo->create($data);
        }
        $this->redirect('/?action=edit');
    }

    public function deleteAtuacao(int $id): void {
        $this->auth->requireAuth();
        $this->atuacaoRepo->delete($id);
        $this->redirect('/?action=edit');
    }

    public function addFormacao(): void {
        $this->auth->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->csrf->validateToken($_POST['_csrf_token'] ?? null)) {
                $this->redirect('/?action=edit');
                return;
            }
            $data = $_POST;
            $data['id_lattes'] = $this->auth->getLoggedInId();
            $this->formacaoRepo->create($data);
        }
        $this->redirect('/?action=edit');
    }

    public function deleteFormacao(int $id): void {
        $this->auth->requireAuth();
        $this->formacaoRepo->delete($id);
        $this->redirect('/?action=edit');
    }

    public function updateFormacao(int $id): void {
        $this->auth->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->formacaoRepo->update($id, $_POST);
        }
        $this->redirect('/?action=edit');
    }

    public function updateAtuacao(int $id): void {
        $this->auth->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->atuacaoRepo->update($id, $_POST);
        }
        $this->redirect('/?action=edit');
    }
}
