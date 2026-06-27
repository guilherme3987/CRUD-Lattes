  </main>
  <script src="/js/script.js"></script>
  <footer class="footer-landing">
    <div class="footer-inner">
      <div class="footer-brand">
        <div class="logo">
          <div class="logo-icon">S</div>
          <span class="logo-name" style="font-size:15px">Scientia Discovery</span>
        </div>
        <p class="footer-desc">Plataforma para consultar produções CAPES, pesquisadores locais da base Lattes e indicadores científicos.</p>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Plataforma</div>
        <ul class="footer-links">
          <?php if ($auth->isAuthenticated()): ?>
            <li><a href="/?action=profile">Início</a></li>
            <li><a href="/?action=profile">Meu Perfil</a></li>
          <?php else: ?>
            <li><a href="/">Início</a></li>
            <li><a href="/?action=list">Pesquisadores</a></li>
            <li><a href="/?action=create">Cadastrar-se</a></li>
            <li><a href="/?action=login">Entrar</a></li>
          <?php endif; ?>
        </ul>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Recursos</div>
        <ul class="footer-links">
          <?php if ($auth->isAuthenticated()): ?>
            <li><a href="/?action=profile">Meu Perfil</a></li>
          <?php else: ?>
            <li><a href="/?action=create">Cadastro</a></li>
            <li><a href="/?action=list">Listagem</a></li>
          <?php endif; ?>
          <li><a href="/css/style.css">Estilos</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bar">
      <span>&copy; 2026 CRUD Lattes — Scientia Discovery</span>
    </div>
  </footer>
</div>
</body>
</html>
