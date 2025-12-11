document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-seguimiento');
  if (form) {
    form.addEventListener('submit', (e) => {
      const input = form.querySelector('.search__input');
      if (!input.value.trim()) {
        e.preventDefault();
        alert('Por favor ingresa un número de seguimiento.');
      }
    });
  }
});

document.addEventListener('click', (e) => {
  const btn = e.target.closest('[data-toggle="sidebar"]');
  if (!btn) return;
  document.querySelector('.admin-sidebar')?.classList.toggle('is-hidden');
});

