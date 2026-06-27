<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-inner">
    <div class="hero-label">
      <span class="hero-label-line"></span>
      Plataforma de descoberta científica
    </div>
    <h1 class="hero-title">Encontre pesquisadores locais e produções científicas na base pública da CAPES.</h1>

    <div class="hero-search">
      <form class="search-bar" action="/" method="GET">
        <input type="hidden" name="action" value="search">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;color:#94a3b8"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input name="q" placeholder="Buscar por ORCID">
        <button class="btn btn-primary" type="submit">Buscar</button>
      </form>
      <div class="search-suggest">
        <span class="search-suggest-label">Ex:</span>
        <span>0000-0002-1825-0097</span>
      </div>

    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-label">Currículos indexados</div>
        <div class="stat-value"><?= $total_pesquisadores ?></div>
        <div class="stat-foot">Plataforma Lattes</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Produções científicas</div>
        <div class="stat-value">-</div>
        <div class="stat-foot">Artigos, capítulos, anais e teses</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Instituições</div>
        <div class="stat-value">-</div>
        <div class="stat-foot">Universidades públicas e privadas</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Áreas CNPq</div>
        <div class="stat-value">-</div>
        <div class="stat-foot">Cobertura por grande área e subárea</div>
      </div>
    </div>
  </div>
</section>


<section class="section">
  <div class="section-inner">
    <div class="section-header">
      <div>
        <div class="section-label">Pesquisadores</div>
        <h2 class="section-title">Pesquisadores na base</h2>
      </div>
      <a href="/?action=list" class="section-link">Ver todos &rarr;</a>
    </div>

    <div class="researcher-grid">
      <?php if (empty($pesquisadores)): ?>
        <div class="researcher-empty" style="grid-column:1/-1">Nenhum pesquisador encontrado.</div>
      <?php else: ?>
        <?php foreach ($pesquisadores as $p): ?>
        <a href="/?action=show&id=<?= urlencode($p['id_lattes']) ?>" class="researcher-grid-card">
          <div class="rg-icon"><?= strtoupper(substr($p['nome_completo'], 0, 1)) ?></div>
          <div class="rg-name"><?= htmlspecialchars($p['nome_completo']) ?></div>
          <div class="rg-meta"><?= htmlspecialchars($p['cidade_nascimento'] ?? '') ?><?= ($p['cidade_nascimento'] && $p['pais_nascimento']) ? ' &middot; ' : '' ?><?= htmlspecialchars($p['pais_nascimento'] ?? '') ?></div>
          <?php if ($auth->isAuthenticated()): ?><div class="rg-id">ID: <?= htmlspecialchars($p['id_lattes']) ?></div><?php endif; ?>
        </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
