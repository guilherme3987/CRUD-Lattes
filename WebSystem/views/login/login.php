<div class="login-page">
  <div class="login-card">

    <?php if (!empty($erro)): ?>
      <div class="login-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form class="form" method="POST">
      <?= $csrf->renderHiddenField() ?>
      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required placeholder="seu@email.com">
      </div>
      <div class="form-group">
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required placeholder="Sua senha">
      </div>
      <div class="form-actions">
        <button class="btn btn-primary btn-login" type="submit">Entrar</button>
      </div>
    </form>

    <div class="login-footer">
      <a href="/" class="link">Voltar para o início</a>
    </div>
  </div>
</div>
