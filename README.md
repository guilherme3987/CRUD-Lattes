# CRUD Lattes Scientia Discovery

Sistema web para consulta, cadastro e gerenciamento de currículos da plataforma Lattes (CNPq). O projeto importa dados reais de currículos em XML, extrai informações acadêmicas e profissionais, e disponibiliza uma interface para navegação pública e edição autenticada.

O projeto é dividido em dois componentes principais: um **pipeline ETL** em Python que faz a leitura, parse e importação dos XMLs Lattes para o MySQL, e uma **aplicação web** em PHP que consome esses dados e oferece a interface para os usuários finais.

Disponível em: [https://crud-lattes.onrender.com/](https://crud-lattes.onrender.com/)\
Deploy do banco de dados no [aiven.io](https://aiven.io/)\
Deploy da aplicação no [render.com](https://render.com/)
---

## Tecnologias utilizadas

| Tecnologia | Função |
|------------|--------|
| **PHP 8.1+** | Linguagem principal do backend. Responsável pelo roteamento, controle de sessão, renderização de views e lógica de negócio. |
| **MySQL 8** | Banco de dados relacional. Armazena pesquisadores, formações acadêmicas e atuações profissionais. |
| **PDO** | Camada de abstração de banco no PHP. Toda comunicação com o MySQL é feita via `PDO` com prepared statements. |
| **HTML5** | Estrutura semântica das páginas. |
| **CSS3** | Estilização com variáveis CSS, grid, flexbox e suporte a tema escuro. Nenhum framework CSS foi utilizado. |
| **JavaScript Vanilla** | Interações leves de front-end: toggle de resumo, exibição de formulários. |
| **Composer** | Autoload PSR-4 das classes PHP. |
| **Python 3.12** | Execução do script de importação dos XMLs Lattes para o banco. |
| **mysql-connector-python** | Conexão do script Python com o MySQL. |
| **Arquitetura MVC** | Padrão de organização do código da aplicação web. |

---

## Estrutura do projeto

```text
CRUD-Lattes/
│
├── WebSystem/                        # Aplicação web PHP
│   ├── index.php                     # Front controller (ponto de entrada)
│   ├── .env                          # Credenciais do banco de dados
│   ├── composer.json                 # Configuração do autoload PSR-4
│   │
│   ├── config/
│   │   └── database.php             # Singleton de conexão PDO
│   │
│   ├── core/
│   │   ├── Router.php               # Roteador de ações
│   │   └── BaseController.php       # Base com render(), redirect(), auth e csrf
│   │
│   ├── controllers/
│   │   └── PesquisadorController.php # Controlador principal
│   │
│   ├── models/
│   │   ├── Pesquisador.php          # Model (proxies estáticos para o repository)
│   │   ├── FormacaoAcademica.php
│   │   └── AtuacaoProfissional.php
│   │
│   ├── repositories/
│   │   ├── PesquisadorRepository.php
│   │   ├── FormacaoAcademicaRepository.php
│   │   └── AtuacaoProfissionalRepository.php
│   │
│   ├── services/
│   │   ├── AuthService.php          # Autenticação via sessão
│   │   └── CsrfService.php          # Proteção CSRF
│   │
│   ├── views/
│   │   ├── index.php                # Landing page
│   │   ├── layout/
│   │   │   ├── header.php           # Head, nav, tema
│   │   │   └── footer.php           # Footer, scripts
│   │   ├── login/
│   │   │   └── login.php            # Formulário de login
│   │   └── pesquisador/
│   │       ├── index.php            # Lista de pesquisadores
│   │       ├── show.php             # Perfil público
│   │       ├── create.php           # Cadastro
│   │       └── edit.php             # Edição inline
│   │
│   ├── css/
│   │   └── style.css               # Folha de estilos completa
│   │
│   ├── js/
│   │   └── script.js               # Interações JS
│   │
│   └── vendor/                      # Autoload Composer
│
├── GetData/                          # Pipeline de importação
│   ├── .env                         # Credenciais do banco
│   ├── requirements.txt             # Dependências Python
│   ├── Script_Banco.sql             # Schema MySQL
│   ├── format_xml.py                # Pretty-printer de XML
│   ├── script_database.py           # Parsing e importação
│   ├── migrate_senha.php            # Migração de senha para bcrypt
│   ├── Diagrama.png                 # Diagrama do banco
│   └── lattes_data_xml_formatados/  # XMLs de currículo
│       ├── 7401907691814937.xml
│       ├── 6716225567627323.xml
│       └── ... (6 arquivos adicionais)
│
└── README.md
```

### O que cada pasta faz

- **`WebSystem/config/`** Configuração da conexão com o banco via PDO. Implementa o padrão Singleton para que toda a aplicação reuse a mesma instância de conexão.

- **`WebSystem/core/`** Classes fundamentais do framework: o `Router` mapeia ações a handlers, e o `BaseController` fornece `render()` e `redirect()` para todos os controladores.

- **`WebSystem/controllers/`** Contém apenas o `PesquisadorController`, que concentra toda a lógica de cada rota: exibir landing, listar, criar, editar, deletar, fazer login, buscar e gerenciar formações e atuações.

- **`WebSystem/models/`** Models que atuam como fachadas estáticas para os repositórios. São mantidos para compatibilidade com o padrão MVC tradicional.

- **`WebSystem/repositories/`** Contém as consultas SQL propriamente ditas. Cada repositório encapsula todo o acesso a dados de uma entidade. Nenhuma SQL aparece fora daqui.

- **`WebSystem/services/`** Serviços transversais: `AuthService` cuida da autenticação via sessão com bcrypt, `CsrfService` protege formulários contra ataques CSRF.

- **`WebSystem/views/`** Templates PHP com o mínimo de lógica possível. Divididos em layout (header/footer) e páginas específicas. Recebem dados prontos dos controladores.

- **`WebSystem/css/`** Único arquivo `style.css` com toda a estilização: variáveis CSS, tema claro/escuro, grid responsivo, componentes.

- **`WebSystem/js/`** JavaScript modular mínimo para interações de interface.

- **`GetData/`** Scripts Python e SQL para extrair, transformar e carregar (ETL) os currículos Lattes do formato XML para o MySQL.

---

## Arquitetura MVC

O sistema segue o padrão **Model-View-Controller (MVC)** para separar as responsabilidades em três camadas distintas. O objetivo é manter o código organizado, testável e de fácil manutenção.

### Por que MVC?

- **Separação clara** entre lógica de negócio, apresentação e dados.
- **Manutenibilidade**: cada camada pode ser alterada isoladamente.
- **Reuso**: os models e repositórios podem ser utilizados por diferentes controladores.
- **Padronização**: novos desenvolvedores reconhecem a estrutura imediatamente.

### Fluxo de uma requisição

```text
Usuário (navegador)
       │
       ▼
  index.php (front controller)
       │
       ▼
    Router (identifica a ação)
       │
       ▼
  Controller (processa a requisição)
       │
       ├──► Model / Repository (consulta o banco)
       │         │
       │         ▼
       │    Banco de Dados (MySQL)
       │         │
       │         ▼
       │◄── Model / Repository (retorna dados)
       │
       ▼
  Controller (organiza os dados)
       │
       ▼
    View (renderiza HTML)
       │
       ▼
  Navegador (exibe a página)
```

### Etapas detalhadas

1. **O usuário acessa uma URL** como `/?action=profile`.

2. **O front controller** (`index.php`) inicia a sessão, carrega o autoload e instancia o `Router`.

3. **O Router** verifica o parâmetro `action` na URL. Se a ação existir no mapa, executa o handler correspondente normalmente um método do controlador.

4. **O Controller** recebe a requisição. Ele valida permissões (autenticação), lê parâmetros (`$_GET`, `$_POST`), e invoca o repositório apropriado para buscar ou persistir dados.

5. **O Repository** executa uma consulta SQL via PDO com prepared statements, retorna arrays associativos.

6. **O Controller** pega os dados retornados e chama `render()`, passando o nome da view e os dados.

7. **A View** recebe os dados via variáveis extraídas com `extract()` e monta o HTML. A lógica na view se limita a loops e condicionais para exibição.

8. **O HTML completo** (header + view + footer) é enviado ao navegador.

---

## Controllers

O controlador é o orquestrador da aplicação. Ele recebe a requisição, decide quais dados são necessários, consulta os repositórios e repassa os dados para a view correta.

No projeto, todo o controle está centralizado em `PesquisadorController`, que estende `BaseController` e herda os métodos `render()` e `redirect()`.

```php
class PesquisadorController extends BaseController
{
    public function profile(): void
    {
        $this->auth->requireAuth();
        $this->show($this->auth->getLoggedInId());
    }
}
```

Explicação linha a linha:

- **`class PesquisadorController extends BaseController`** Define o controlador e herda de `BaseController`, que fornece os métodos `render()` e `redirect()`, além das instâncias de `AuthService` e `CsrfService`.

- **`public function profile(): void`** Método que manipula a rota `/profile`. É público pois é chamado pelo Router.

- **`$this->auth->requireAuth()`** Bloqueia o acesso se o usuário não estiver logado. Redireciona para a página de login.

- **`$this->show($this->auth->getLoggedInId())`** Delega a exibição para o método `show()`, passando o ID do pesquisador autenticado. Isso evita duplicação de lógica.

```php
public function show(string $id): void
{
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
```

Explicação:

1. **`$this->pesquisadorRepo->getById($id)`** Consulta o repositório que executa o `SELECT * FROM pesquisador WHERE id_lattes = ?`.

2. **`if (!$pesquisador)`** Se o ID não existir no banco, redireciona para a home.

3. **`$this->render('pesquisador/show', [...])`** Chama o método herdado que carrega o header, a view `views/pesquisador/show.php` e o footer. Os dados passados no array são extraídos como variáveis dentro da view.

---

## Models e Repositories

O projeto utiliza o **Repository Pattern** para separar a lógica de acesso a dados dos models. As classes em `models/` são fachadas finas que delegam chamadas estáticas para os repositórios, enquanto os repositórios concentram todo o SQL.

```php
// repositories/PesquisadorRepository.php
public function getById(string $idLattes): ?array
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM pesquisador WHERE id_lattes = ?");
    $stmt->execute([$idLattes]);
    $result = $stmt->fetch();
    return $result ?: null;
}
```

Explicação linha a linha:

- **`Database::getConnection()`** Obtém a instância singleton da conexão PDO, configurada com `ERRMODE_EXCEPTION`, fetch mode associativo e `EMULATE_PREPARES = false`.

- **`$conn->prepare(...)`** Cria um prepared statement com um placeholder `?`. O MySQL analisa a sintaxe uma vez e reutiliza o plano de execução.

- **`$stmt->execute([$idLattes])`** Substitui o placeholder pelo valor real de forma segura. O PDO escapa o valor automaticamente, eliminando risco de SQL injection.

- **`$stmt->fetch()`** Retorna a primeira linha como um array associativo (ex: `['id_lattes' => '...', 'nome_completo' => '...']`) ou `false` se não encontrar.

- **`return $result ?: null`** Converte o `false` do fetch para `null`, facilitando a verificação no controlador com `if (!$pesquisador)`.

```php
// PesquisadorRepository.php
public function create(array $data): void
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("
        INSERT INTO pesquisador (id_lattes, email, senha, nome_completo, pais_nascimento, cidade_nascimento, orcid_id, resumo_cv)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $data['id_lattes'],
        $data['email'],
        $data['senha'],
        $data['nome_completo'],
        $data['pais_nascimento'],
        $data['cidade_nascimento'],
        $data['orcid_id'],
        $data['resumo_cv'],
    ]);
}
```

O `create()` segue o mesmo padrão de prepared statements. As colunas são listadas explicitamente no `INSERT`, e os valores passados posicionalmente no `execute()`. Isso evita qualquer injeção e torna a query auto-documentada.

---

## Views

As views contêm **apenas apresentação**. Não realizam consultas ao banco, não processam formulários e não contêm lógica de negócio. Elas recebem dados prontos do controlador e os exibem.

```php
<!-- views/pesquisador/show.php -->
<h1><?= htmlspecialchars($pesquisador['nome_completo']) ?></h1>
```

Explicação:

- **`<?= ... ?>`** Tag de saída do PHP (short echo tag). Equivalente a `<?php echo ... ?>`. Disponivel a partir do PHP 5.4+.

- **`htmlspecialchars($pesquisador['nome_completo'])`** Converte caracteres especiais HTML (`<`, `>`, `"`, `&`) em suas entidades. Impede ataques XSS. Sempre que um dado vindo do banco ou do usuário for exibido, ele deve passar por esta função.

- **`$pesquisador`** Variável disponível na view porque o controlador fez `$this->render('pesquisador/show', ['pesquisador' => $dados])`. O `BaseController::render()` usa `extract($data)` para transformar cada chave do array em uma variável.

```php
<!-- views/pesquisador/show.php -->
<dl class="data-list">
    <dt>ID Lattes</dt>
    <dd><?= htmlspecialchars($pesquisador['id_lattes']) ?></dd>

    <dt>ORCID</dt>
    <dd><?= htmlspecialchars($pesquisador['orcid_id']) ?></dd>

    <dt>País</dt>
    <dd><?= htmlspecialchars($pesquisador['pais_nascimento']) ?></dd>

    <dt>Cidade</dt>
    <dd><?= htmlspecialchars($pesquisador['cidade_nascimento']) ?></dd>
</dl>
```

A separação é rigorosa: a view itera sobre arrays, aplica `htmlspecialchars()` em cada valor, monta classes CSS condicionais com operadores ternários e nada mais. Não há SQL, não há `$_POST`, não há lógica de validação.

---

## Fluxo completo de uma requisição

Exemplo prático: o usuário acessa `/?action=show&id=7401907691814937`.

1. **Front controller** (`index.php`): `session_start()`, carrega autoload, cria Router e Controller, lê `$_GET['action'] = 'show'` e `$_GET['id'] = '7401907691814937'`, chama `$router->dispatch('show', '7401907691814937')`.

2. **Router**: encontra a rota `'show'` e executa a closure `function (?string $id) use ($controller) { $controller->show($id); }`.

3. **Controller**: `PesquisadorController::show('7401907691814937')` é chamado.

4. **Repository**: `$this->pesquisadorRepo->getById('7401907691814937')` executa `SELECT * FROM pesquisador WHERE id_lattes = ?` com prepared statement.

5. **Banco de Dados**: retorna o registro do pesquisador como array associativo.

6. **Controller**: monta o array de dados com o pesquisador, suas formações e atuações, e chama `$this->render('pesquisador/show', $data)`.

7. **BaseController::render()**: faz `extract($data)`, inclui `header.php`, `views/pesquisador/show.php`, `footer.php`.

8. **View**: exibe o nome, dados pessoais, resumo expansível, lista de formações acadêmicas e atuações profissionais dentro de elementos `<details>`.

9. **Navegador**: recebe o HTML completo e renderiza a página.

---

## Organização do CSS

O CSS do projeto está todo em um único arquivo (`css/style.css`, 315 linhas) e organizado em blocos com comentários. Nenhum framework externo foi utilizado.

### Variáveis CSS

Todas as cores, espaçamentos e sombras são definidos como variáveis no `:root`. A mudança para o tema escuro é feita redefinindo essas mesmas variáveis dentro de `[data-theme="dark"]`.

```css
:root {
  --primary: #2563eb;
  --primary-hover: #1d4ed8;
  --bg-page: #f8f9fc;
  --bg-card: #ffffff;
  --text-primary: #1a1a2e;
  --text-secondary: #475569;
  --border: #e2e8f0;
}
```

### Tema Dark

O atributo `data-theme="dark"` é aplicado no elemento `<html>`. O CSS sobrescreve as variáveis com cores escuras, e todos os componentes que usam essas variáveis se adaptam automaticamente sem necessidade de regras CSS separadas para cada componente.

```css
[data-theme="dark"] {
  --bg-page: #0f172a;
  --bg-card: #1e293b;
  --text-primary: #e2e8f0;
  --primary: #3b82f6;
  --border: #334155;
}
```

### Componentes

Os estilos seguem uma abordagem de classes utilitárias e componentes nomeados:

- **`.card`** Container com borda, padding e background que se adapta ao tema.
- **`.btn` / `.btn-primary` / `.btn-outline` / `.btn-danger`** Botões com variantes de cor.
- **`.table` / `.table-wrap`** Tabelas responsivas com scroll horizontal.
- **`.form` / `.form-group` / `.form-row`** Formulários com grid de duas colunas.
- **`.data-list`** Lista de definição em grid (dt/dd lado a lado).
- **`.resumo-text`** Bloco de texto com altura máxima truncada e transição para expansão.
- **`.search-bar`** Barra de busca com ícone e input.
- **`.stats-grid` / `.stat-card`** Cards de estatísticas na landing page.

### Responsividade

O layout usa `max-width: 1280px` centralizado, `flexbox` e `grid` com breakpoints em:

- **860px** Grid de 2 colunas vira 1 coluna.
- **768px** Section headers empilham verticalmente.
- **640px** Título da hero reduz, grid de features vira 1 coluna.
- **480px** Stats grid vira 1 coluna.

```css
.grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

@media (max-width: 860px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
}
```

---

## Organização do JavaScript

O JS é escrito em JavaScript puro (Vanilla), sem dependências externas. As funções são globais e chamadas via atributos `onclick` no HTML.

```javascript
(function () {
  'use strict';

  window.toggleResumo = function () {
    var el = document.getElementById('resumoText');
    var btn = document.getElementById('resumoToggle');
    if (!el || !btn) return;
    el.classList.toggle('expanded');
    btn.textContent = el.classList.contains('expanded') ? 'Ver menos' : 'Ver mais';
  };
})();
```

**`toggleResumo()`** Alterna a classe `.expanded` no texto do resumo, que remove o `max-height` limitado (transição CSS de 0.35s). Troca o texto do botão entre "Ver mais" e "Ver menos".

```javascript
window.toggleForm = function (id, button) {
  var wrapper = document.getElementById(id);
  wrapper.classList.toggle("hidden");

  if (wrapper.classList.contains("hidden")) {
    button.textContent = "+ Adicionar";
    button.classList.remove("btn-danger");
    button.classList.add("btn-primary");
  } else {
    button.textContent = "Cancelar";
    button.classList.remove("btn-primary");
    button.classList.add("btn-danger");
  }
};
```

**`toggleForm(id, button)`** Exibe ou oculta um formulário de adição (formação ou atuação). Alterna o texto e a classe do botão entre "Adicionar" (primário) e "Cancelar" (perigo).

---

## Banco de Dados

O banco possui três tabelas relacionadas:

```text
pesquisador (id_lattes, email, senha, nome_completo, pais_nascimento,
             cidade_nascimento, orcid_id, resumo_cv)
      │
      ├── 1:N ── formacao_academica (id, nivel, instituicao, curso, status,
      │                              ano_inicio, ano_conclusao)
      │
      └── 1:N ── atuacao_profissional (id, instituicao, ano_inicio, ano_fim,
                                       tipo_vinculo, enquadramento_funcional)
```

### Relacionamentos

- **Pesquisador 1:N Formação** Um pesquisador pode ter várias formações (graduação, mestrado, doutorado, pós-doutorado). A chave estrangeira `id_lattes` na tabela `formacao_academica` referencia `pesquisador.id_lattes` com `ON DELETE CASCADE`.

- **Pesquisador 1:N Atuação** Um pesquisador pode ter várias atuações profissionais. Mesmo esquema de chave estrangeira com cascade.

### Esquema SQL

```sql
CREATE TABLE pesquisador (
    id_lattes       VARCHAR(50)  NOT NULL PRIMARY KEY,
    email           VARCHAR(255) NOT NULL UNIQUE,
    senha           VARCHAR(255) NOT NULL,
    nome_completo   VARCHAR(255) NOT NULL,
    pais_nascimento VARCHAR(100) NOT NULL,
    cidade_nascimento VARCHAR(100) NOT NULL,
    orcid_id        VARCHAR(100) NOT NULL,
    resumo_cv       TEXT         NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE formacao_academica (
    id             INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_lattes      VARCHAR(50)  NOT NULL,
    nivel          VARCHAR(50)  NOT NULL,
    instituicao    VARCHAR(255) NOT NULL,
    curso          VARCHAR(255) NOT NULL,
    status         VARCHAR(50)  NOT NULL,
    ano_inicio     YEAR         NOT NULL,
    ano_conclusao  YEAR         NOT NULL,
    FOREIGN KEY (id_lattes) REFERENCES pesquisador(id_lattes) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE atuacao_profissional (
    id                      INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_lattes               VARCHAR(50)  NOT NULL,
    instituicao             VARCHAR(255) NOT NULL,
    ano_inicio              YEAR         NOT NULL,
    ano_fim                 YEAR         NOT NULL,
    tipo_vinculo            VARCHAR(150) NOT NULL,
    enquadramento_funcional VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_lattes) REFERENCES pesquisador(id_lattes) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
```

O `ON DELETE CASCADE` garante que, ao remover um pesquisador, todas as suas formações e atuações sejam removidas automaticamente.

---

## Convenções do projeto

### Nomenclatura de arquivos

| Camada | Padrão | Exemplo |
|--------|--------|---------|
| Controller | `NomeController.php` | `PesquisadorController.php` |
| Model | `Nome.php` | `Pesquisador.php` |
| Repository | `NomeRepository.php` | `PesquisadorRepository.php` |
| Service | `NomeService.php` | `AuthService.php` |
| View (página) | `nome-da-pagina.php` | `show.php`, `create.php` |
| View (layout) | `nome-do-componente.php` | `header.php`, `footer.php` |

### Nomenclatura de métodos

Os métodos do controlador seguem convenção RESTful simplificada:

| Método | Ação | Rota |
|--------|------|------|
| `index()` | Página inicial / dashboard | `?action=default` |
| `list()` | Listar todos os registros | `?action=list` |
| `show($id)` | Exibir um registro | `?action=show&id=X` |
| `create()` | Exibir form + processar POST | `?action=create` |
| `edit()` | Exibir form + processar POST | `?action=edit` |
| `delete()` | Remover registro | `?action=delete` |
| `addAtuacao()` | Adicionar atuação (POST) | `?action=addAtuacao` |
| `deleteAtuacao($id)` | Remover atuação | `?action=deleteAtuacao&id=X` |
| `updateAtuacao($id)` | Atualizar atuação (POST) | `?action=updateAtuacao&id=X` |

### Namespaces e autoload

O autoload segue PSR-4 com mapeamento `App\ => ./` (raiz do `WebSystem/`). Exemplos:

- `App\Core\Router` → `core/Router.php`
- `App\Controllers\PesquisadorController` → `controllers/PesquisadorController.php`
- `App\Repositories\PesquisadorRepository` → `repositories/PesquisadorRepository.php`

---

## Como executar o projeto

### Pré-requisitos

- **PHP 8.1 ou superior** com extensões `pdo_mysql` e `mbstring` habilitadas.
- **MySQL 8** rodando localmente ou em servidor acessível.
- **Composer** instalado globalmente.
- **Python 3.12+** (opcional, apenas para importar dados).
- Servidor web embutido do PHP ou Apache/Nginx.

### Passo a passo

#### 1. Configurar o banco de dados

Execute o script SQL para criar o schema e as tabelas:

```bash
mysql -u root -p < GetData/Script_Banco.sql
```

#### 2. Configurar credenciais

Edite o arquivo `WebSystem/.env` com as credenciais do seu MySQL:

```
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=root
DB_NAME=database_lattes
DB_PORT=3306
```

#### 3. Instalar dependências PHP

```bash
cd WebSystem
composer install
```

#### 4. Importar dados (opcional)

Se você possui arquivos XML de currículo Lattes:

```bash
cd GetData
pip install -r requirements.txt
python script_database.py
```

Os XMLs de exemplo já estão em `lattes_data_xml_formatados/`.

#### 5. Iniciar o servidor

```bash
cd WebSystem
php -S localhost:8000
```

Acesse `http://localhost:8000` no navegador.

Para fazer login, utilize o email e senha gerados pelo script de importação. A senha padrão é `123456`. O email é gerado automaticamente no formato `primeiro.ultimo@gmail.com` (sem acentos).

---

## Boas práticas adotadas

- **Separação de responsabilidades** Cada classe tem uma função bem definida. Controllers orquestram, repositories acessam dados, views exibem.

- **MVC** Padrão arquitetural que mantém o código organizado e facilita a manutenção.

- **HTML sem lógica pesada** As views contêm apenas loops, condicionais e chamadas a `htmlspecialchars()`. Nenhuma lógica de negócio ou SQL.

- **SQL apenas nos Repositories** Nenhuma query SQL aparece fora das classes `*Repository.php`. Isso centraliza as consultas e facilita auditoria.

- **Controllers enxutos** Métodos com poucas linhas, cada um responsável por uma única ação. Validação de autenticação é delegada ao `AuthService`.

- **CSS organizado com variáveis** Cores, sombras e espaçamentos são definidos como variáveis CSS, permitindo tema escuro sem duplicação.

- **Dark Mode** Alternância de tema via atributo `data-theme` com transição suave de `background` e `color`.

- **Componentização no CSS** Classes reutilizáveis como `.card`, `.btn`, `.table` que podem ser combinadas livremente.

- **Responsividade** Layout adaptável com breakpoints em 860px, 768px, 640px e 480px.

- **Prepared Statements** Todas as consultas SQL usam `prepare()` + `execute()` com placeholders. Zero SQL injection.

- **htmlspecialchars()** Toda saída de dados vindos do banco ou do usuário é sanitizada com `htmlspecialchars()` na view.

- **Proteção CSRF** Formulários POST incluem um token único por sessão, validado com `hash_equals()`.

- **Session Regeneration** `session_regenerate_id(true)` no login para prevenir fixation de sessão.

- **Reutilização de componentes** O `BaseController::render()` carrega header/footer automático, injeta auth e csrf em todas as views.

---

## Padrões de desenvolvimento

### Tratamento de erros

O PDO é configurado com `ERRMODE_EXCEPTION`, o que significa que qualquer falha no banco lança uma exceção. Em ambiente de desenvolvimento, o PHP exibirá o erro diretamente. Para produção, recomenda-se implementar um `try/catch` global no `index.php`:

```php
try {
    $router->dispatch($action, $id);
} catch (\PDOException $e) {
    http_response_code(500);
    echo 'Erro interno do servidor.';
    // Log: error_log($e->getMessage());
}
```

### Validação de dados

- **Campos obrigatórios** Validados no controller antes de chamar o repository (ex: verificação de email duplicado no `create()`).
- **IDs de rota** O parâmetro `id` da URL é recebido como `?string` e convertido para `int` quando necessário (ex: `deleteAtuacao((int)$id)`).
- **CSRF** Todo POST é protegido por token CSRF. Se inválido, a ação é abortada com redirect.

### Segurança

- **Senhas** Hash bcrypt via `password_hash(PASSWORD_BCRYPT)`.MD5 para migração de dados importados.
- **Sessão** Uso de `session_regenerate_id(true)` no login.
- **SQL Injection** Zero concatenação de strings em SQL. Apenas prepared statements com placeholders.
- **XSS** `htmlspecialchars()` em toda saída de dados dinâmicos nas views.

---

## Exemplos comentados

### Controller

```php
public function search(): void
{
    // Redireciona usuários logados para o perfil
    if ($this->auth->isAuthenticated()) {
        $this->redirect('/?action=profile');
    }

    // Lê o termo de busca da query string
    $q = $_GET['q'] ?? '';

    if ($q !== '') {
        // Busca por ORCID (LIKE %termo%)
        $pesquisadores = $this->pesquisadorRepo->searchByOrcid($q);

        // Se encontrou exatamente 1, redireciona direto pro perfil
        if (count($pesquisadores) === 1) {
            $this->redirect('/?action=show&id=' . urlencode($pesquisadores[0]['id_lattes']));
        }
    }

    // Caso contrário, volta pra home
    $this->redirect('/');
}
```

O método `search()` ilustra um padrão comum no projeto: validação de autenticação no topo, leitura de parâmetros, consulta ao repositório, decisão baseada no resultado e redirect como resposta. O `urlencode()` garante que caracteres especiais no ID não quebrem a URL.

### Repository

```php
public function update(string $idLattes, array $data): void
{
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
```

Note que o `UPDATE` lista explicitamente as colunas que podem ser alteradas. Isso impede que campos não esperados (como `senha` ou `id_lattes`) sejam modificados acidentalmente, mesmo que enviados no formulário. O operador `?? null` garante que campos opcionais não causem erro se ausentes.

### View

```php
<!-- Listagem de formações acadêmicas com dropdown -->
<details class="dropdown-card">
    <summary class="dropdown-summary">
        Formação Acadêmica
        <span class="dropdown-count"><?= count($formacoes) ?></span>
    </summary>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Nível</th>
                    <th>Instituição</th>
                    <th>Curso</th>
                    <th>Ano</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($formacoes as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['nivel']) ?></td>
                    <td><?= htmlspecialchars($f['instituicao']) ?></td>
                    <td><?= htmlspecialchars($f['curso']) ?></td>
                    <td><?= (int)$f['ano_conclusao'] ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</details>
```

---

## Licença

Este projeto é disponibilizado para fins educacionais e de pesquisa. Dados importados da Plataforma Lattes pertencem aos respectivos pesquisadores e ao CNPq.
