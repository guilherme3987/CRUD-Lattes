<div class="page-header">
  <h1>Cadastrar-se</h1>
  <a class="btn btn-outline" href="/">Voltar</a>
</div>

<div class="card">
  <?php if (!empty($erro)): ?>
    <div class="login-erro" style="margin-bottom:1rem"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>
  <form class="form" method="POST">
    <?= $csrf->renderHiddenField() ?>
    <div class="form-group">
      <label>ID Lattes *</label>
      <input name="id_lattes" required placeholder="Ex: 1234567890123456">
    </div>
    <div class="form-group">
      <label>Nome Completo *</label>
      <input name="nome_completo" required placeholder="Nome do pesquisador">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>E-mail *</label>
        <input type="email" name="email" required placeholder="email@exemplo.com">
      </div>
      <div class="form-group">
        <label>Senha *</label>
        <input type="password" name="senha" required placeholder="Mínimo 6 caracteres">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>País de Nascimento</label>
        <input name="pais_nascimento" placeholder="Brasil">
      </div>
      <div class="form-group">
        <label>Cidade de Nascimento</label>
        <input name="cidade_nascimento" placeholder="Cidade">
      </div>
    </div>
    <div class="form-group">
      <label>ORCID ID</label>
      <input name="orcid_id" placeholder="https://orcid.org/0000-0000-0000-0000">
    </div>
    <div class="form-group">
      <label>Resumo do CV</label>
      <textarea name="resumo_cv" rows="5" placeholder="Resumo do currículo Lattes..."></textarea>
    </div>
    <div class="form-actions">
      <button class="btn btn-primary">Criar Perfil</button>
      <a class="btn btn-outline" href="/">Cancelar</a>
    </div>
  </form>
</div>
