<div class="page-header">
  <h1>Editar Perfil</h1>
  <a class="btn btn-outline" href="/?action=profile">Voltar</a>
</div>

<div class="card">
  <h2 class="card-title">Dados Gerais</h2>
  <form class="form" method="POST">
    <?= $csrf->renderHiddenField() ?>
    <div class="form-group">
      <label>Nome Completo *</label>
      <input name="nome_completo" required value="<?= htmlspecialchars($pesquisador['nome_completo']) ?>">
    </div>
    <div class="form-group">
      <label>E-mail</label>
      <input type="email" name="email" value="<?= htmlspecialchars($pesquisador['email'] ?? '') ?>">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>País de Nascimento</label>
        <input name="pais_nascimento" value="<?= htmlspecialchars($pesquisador['pais_nascimento'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Cidade de Nascimento</label>
        <input name="cidade_nascimento" value="<?= htmlspecialchars($pesquisador['cidade_nascimento'] ?? '') ?>">
      </div>
    </div>
    <div class="form-group">
      <label>Resumo do CV</label>
      <textarea name="resumo_cv" rows="5"><?= htmlspecialchars($pesquisador['resumo_cv'] ?? '') ?></textarea>
    </div>
    <div class="form-actions">
      <button class="btn btn-primary">Atualizar</button>
    </div>
  </form>
</div>

<div class="card" style="margin-top:1rem">
  <div class="section-header">
    <h2 class="card-title">Formação Acadêmica</h2>
    <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('wrapper-formacao', this)">+ Adicionar</button>
  </div>

  <div class="add-form-wrapper hidden" id="wrapper-formacao">
    <form class="form add-form" method="POST" action="/?action=addFormacao">
      <?= $csrf->renderHiddenField() ?>
      <div class="form-row">
        <input name="nivel" placeholder="Nível" required>
        <input name="instituicao" placeholder="Instituição" required>
      </div>
      <div class="form-row">
        <input name="curso" placeholder="Curso">
        <input name="status" placeholder="Status" value="CONCLUIDO">
      </div>
      <div class="form-row">
        <input name="ano_inicio" placeholder="Ano Início">
        <input name="ano_conclusao" placeholder="Ano Conclusão">
      </div>
      <button class="btn btn-primary btn-sm">Adicionar</button>
    </form>
  </div>

  <?php foreach ($formacoes as $f): ?>
  <form id="fedit-<?= $f['id'] ?>" method="POST" action="/?action=updateFormacao&id=<?= $f['id'] ?>" class="hidden-form"></form>
  <?php endforeach; ?>

  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>Nível</th><th>Instituição</th><th>Curso</th><th>Status</th><th>Início</th><th>Conclusão</th><th></th></tr></thead>
      <tbody>
        <?php if (empty($formacoes)): ?>
          <tr><td colspan="7" class="empty">Nenhuma formação acadêmica.</td></tr>
        <?php else: ?>
          <?php foreach ($formacoes as $f): ?>
          <tr>
            <td><input name="nivel" value="<?= htmlspecialchars($f['nivel']) ?>" form="fedit-<?= $f['id'] ?>" class="cell-input"></td>
            <td><input name="instituicao" value="<?= htmlspecialchars($f['instituicao']) ?>" form="fedit-<?= $f['id'] ?>" class="cell-input"></td>
            <td><input name="curso" value="<?= htmlspecialchars($f['curso'] ?? '') ?>" form="fedit-<?= $f['id'] ?>" class="cell-input"></td>
            <td><input name="status" value="<?= htmlspecialchars($f['status'] ?? '') ?>" form="fedit-<?= $f['id'] ?>" class="cell-input"></td>
            <td><input name="ano_inicio" value="<?= htmlspecialchars($f['ano_inicio'] ?? '') ?>" form="fedit-<?= $f['id'] ?>" class="cell-input"></td>
            <td><input name="ano_conclusao" value="<?= htmlspecialchars($f['ano_conclusao'] ?? '') ?>" form="fedit-<?= $f['id'] ?>" class="cell-input"></td>
            <td>
              <button class="btn btn-primary btn-sm" onclick="document.getElementById('fedit-<?= $f['id'] ?>').submit()">Salvar</button>
              <a class="btn btn-sm btn-danger" href="/?action=deleteFormacao&id=<?= $f['id'] ?>" onclick="return confirm('Excluir?')">x</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="card" style="margin-top:1rem">
  <div class="section-header">
    <h2 class="card-title">Atuação Profissional</h2>
    <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('wrapper-atuacao', this)">+ Adicionar</button>
  </div>

  <div class="add-form-wrapper hidden" id="wrapper-atuacao">
    <form class="form add-form" method="POST" action="/?action=addAtuacao">
      <?= $csrf->renderHiddenField() ?>
      <div class="form-row">
        <input name="instituicao" placeholder="Instituição" required>
        <input name="ano_inicio" placeholder="Ano Início">
      </div>
      <div class="form-row">
        <input name="ano_fim" placeholder="Ano Fim">
        <input name="tipo_vinculo" placeholder="Tipo de Vínculo">
      </div>
      <div class="form-row">
        <input name="enquadramento_funcional" placeholder="Enquadramento">
      </div>
      <button class="btn btn-primary btn-sm">Adicionar</button>
    </form>
  </div>

  <?php foreach ($atuacoes as $a): ?>
  <form id="aedit-<?= $a['id'] ?>" method="POST" action="/?action=updateAtuacao&id=<?= $a['id'] ?>" class="hidden-form"></form>
  <?php endforeach; ?>

  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>Instituição</th><th>Início</th><th>Fim</th><th>Vínculo</th><th>Enquadramento</th><th></th></tr></thead>
      <tbody>
        <?php if (empty($atuacoes)): ?>
          <tr><td colspan="6" class="empty">Nenhuma atuação profissional.</td></tr>
        <?php else: ?>
          <?php foreach ($atuacoes as $a): ?>
          <tr>
            <td><input name="instituicao" value="<?= htmlspecialchars($a['instituicao']) ?>" form="aedit-<?= $a['id'] ?>" class="cell-input"></td>
            <td><input name="ano_inicio" value="<?= htmlspecialchars($a['ano_inicio'] ?? '') ?>" form="aedit-<?= $a['id'] ?>" class="cell-input"></td>
            <td><input name="ano_fim" value="<?= htmlspecialchars($a['ano_fim'] ?? '') ?>" form="aedit-<?= $a['id'] ?>" class="cell-input"></td>
            <td><input name="tipo_vinculo" value="<?= htmlspecialchars($a['tipo_vinculo'] ?? '') ?>" form="aedit-<?= $a['id'] ?>" class="cell-input"></td>
            <td><input name="enquadramento_funcional" value="<?= htmlspecialchars($a['enquadramento_funcional'] ?? '') ?>" form="aedit-<?= $a['id'] ?>" class="cell-input"></td>
            <td>
              <button class="btn btn-primary btn-sm" onclick="document.getElementById('aedit-<?= $a['id'] ?>').submit()">Salvar</button>
              <a class="btn btn-sm btn-danger" href="/?action=deleteAtuacao&id=<?= $a['id'] ?>" onclick="return confirm('Excluir?')">x</a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
