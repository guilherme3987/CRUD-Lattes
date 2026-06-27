<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRUD Lattes — Scientia Discovery</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:opsz,wght@8..60,400;8..60,500;8..60,600;8..60,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="app">
  <header class="header">
    <div class="header-inner">
      <a class="logo" href="/">
        <div class="logo-icon">S</div>
        <div class="logo-text">
          <span class="logo-name">Scientia</span>
          <span class="logo-suffix">Discovery</span>
        </div>
      </a>
      <nav class="nav">
        <?php if ($auth->isAuthenticated()): ?>
          <a href="/?action=profile" class="nav-link <?= $currentAction === 'profile' ? 'active' : '' ?>">Início</a>
          <a href="/?action=profile" class="nav-link <?= $currentAction === 'profile' ? 'active' : '' ?>">Meu Perfil</a>
          <span class="nav-user"><?= htmlspecialchars($auth->getLoggedInName()) ?></span>
          <a href="/?action=logout" class="nav-link nav-logout">Sair</a>
        <?php else: ?>
          <a href="/" class="nav-link <?= ($currentAction === 'index' || $currentAction === 'default') ? 'active' : '' ?>">Início</a>
          <a href="/?action=list" class="nav-link <?= $currentAction === 'list' ? 'active' : '' ?>">Pesquisadores</a>
          <a href="/?action=create" class="nav-link <?= $currentAction === 'create' ? 'active' : '' ?>">Cadastrar-se</a>
          <a href="/?action=login" class="nav-link <?= $currentAction === 'login' ? 'active' : '' ?>">Entrar</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="main">
