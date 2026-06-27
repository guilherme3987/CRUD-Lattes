<div class="page-header">
  <h1><?= htmlspecialchars($pesquisador['nome_completo']) ?></h1>
  <div class="btn-group">
    <?php if ($is_owner): ?>
      <a class="btn btn-primary" href="/?action=edit">Editar</a>
      <a class="btn btn-danger" href="/?action=delete" onclick="return confirm('Excluir este pesquisador?')">Excluir</a>
    <?php else: ?>
      <a class="btn btn-outline" href="/">Voltar</a>
    <?php endif; ?>
  </div>
</div>

<div class="card">
  <h2 class="card-title">Dados Gerais</h2>
  <div class="table-wrap">
    <table class="table">
      <tbody>
        <?php if ($is_owner): ?>
        <tr><th>ID Lattes</th><td><?= htmlspecialchars($pesquisador['id_lattes']) ?></td></tr>
        <?php endif; ?>
        <tr><th>Nome</th><td><?= htmlspecialchars($pesquisador['nome_completo']) ?></td></tr>
        <tr><th>E-mail</th><td><?= htmlspecialchars($pesquisador['email'] ?? '-') ?></td></tr>
        <tr><th>País de Nascimento</th><td><?= htmlspecialchars($pesquisador['pais_nascimento'] ?? '-') ?></td></tr>
        <tr><th>Cidade de Nascimento</th><td><?= htmlspecialchars($pesquisador['cidade_nascimento'] ?? '-') ?></td></tr>
        <tr><th>ORCID</th><td><?= htmlspecialchars($pesquisador['orcid_id'] ?? '-') ?></td></tr>
      </tbody>
    </table>
  </div>
  <?php $resumo = $pesquisador['resumo_cv'] ?? ''; ?>
  <?php if ($resumo !== ''): ?>
  <div class="resumo-block">
    <strong class="resumo-label">Resumo</strong>
    <div class="resumo-text" id="resumoText"><?= htmlspecialchars($resumo) ?></div>
    <button type="button" class="btn btn-link btn-sm resumo-toggle" id="resumoToggle" onclick="toggleResumo()">Ver mais</button>
  </div>
  <?php endif; ?>
</div>

<details class="dropdown-card" <?= !empty($formacoes) ? 'open' : '' ?>>
  <summary class="dropdown-summary">Formação Acadêmica <span class="dropdown-count"><?= count($formacoes) ?></span></summary>
  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>Nível</th><th>Instituição</th><th>Curso</th><th>Status</th><th>Início</th><th>Conclusão</th></tr></thead>
      <tbody>
        <?php if (empty($formacoes)): ?>
          <tr><td colspan="6" class="empty">Nenhuma formação acadêmica cadastrada.</td></tr>
        <?php else: ?>
          <?php foreach ($formacoes as $f): ?>
          <tr>
            <td><?= htmlspecialchars($f['nivel']) ?></td>
            <td><?= htmlspecialchars($f['instituicao']) ?></td>
            <td><?= htmlspecialchars($f['curso'] ?? '-') ?></td>
            <td><?= htmlspecialchars($f['status'] ?? '-') ?></td>
            <td><?= htmlspecialchars($f['ano_inicio'] ?? '-') ?></td>
            <td><?= htmlspecialchars($f['ano_conclusao'] ?? '-') ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</details>

<details class="dropdown-card" <?= !empty($atuacoes) ? 'open' : '' ?>>
  <summary class="dropdown-summary">Atuação Profissional <span class="dropdown-count"><?= count($atuacoes) ?></span></summary>
  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>Instituição</th><th>Início</th><th>Fim</th><th>Vínculo</th><th>Enquadramento</th></tr></thead>
      <tbody>
        <?php if (empty($atuacoes)): ?>
          <tr><td colspan="5" class="empty">Nenhuma atuação profissional cadastrada.</td></tr>
        <?php else: ?>
          <?php foreach ($atuacoes as $a): ?>
          <tr>
            <td><?= htmlspecialchars($a['instituicao']) ?></td>
            <td><?= htmlspecialchars($a['ano_inicio'] ?? '-') ?></td>
            <td><?= htmlspecialchars($a['ano_fim'] ?? '-') ?></td>
            <td><?= htmlspecialchars($a['tipo_vinculo'] ?? '-') ?></td>
            <td><?= htmlspecialchars($a['enquadramento_funcional'] ?? '-') ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</details>
