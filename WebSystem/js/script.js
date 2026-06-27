(function () {
  'use strict';

  // ---------- TOGGLE RESUMO ----------
  window.toggleResumo = function () {
    var el = document.getElementById('resumoText');
    var btn = document.getElementById('resumoToggle');
    if (!el || !btn) return;
    el.classList.toggle('expanded');
    btn.textContent = el.classList.contains('expanded') ? 'Ver menos' : 'Ver mais';
  };

  // ---------- TOGGLE ADD FORM ----------
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

})();
