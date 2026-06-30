<div class="page-header">
  <h1>Pesquisadores</h1>
  <?php if ($auth->isAuthenticated()): ?>
    <a class="btn btn-primary" href="/?action=create">Novo Pesquisador</a>
  <?php endif; ?>
</div>

<div class="card">
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>País</th>
          <th>Cidade</th>
          <th>ORCID</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($pesquisadores)): ?>
          <tr><td colspan="5" class="empty">Nenhum pesquisador encontrado.</td></tr>
        <?php else: ?>
          <?php foreach ($pesquisadores as $p): ?>
          <tr>
            <td class="text-nowrap"><a href="/?action=show&id=<?= urlencode($p['id_lattes']) ?>"><?= htmlspecialchars($p['nome_completo']) ?></a></td>
            <td><?= htmlspecialchars($p['pais_nascimento'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['cidade_nascimento'] ?? '-') ?></td>
            <td><?= htmlspecialchars($p['orcid_id'] ?? '-') ?></td>
            <td class="actions">
              <?php if ($auth->isAuthenticated() && $auth->getLoggedInId() === $p['id_lattes']): ?>
                <a class="btn btn-sm btn-outline" href="/?action=edit">Editar</a>
                <a class="btn btn-sm btn-danger" href="/?action=delete" onclick="return confirm('Excluir este pesquisador?')">Excluir</a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
