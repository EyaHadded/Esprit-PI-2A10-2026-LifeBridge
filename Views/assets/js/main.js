// Spinner
window.addEventListener('load', () => {
  const s = document.getElementById('spinner');
  if (s) setTimeout(() => s.classList.remove('show'), 300);
});
// Back to top
const btt = document.querySelector('.btn-back-to-top');
if (btt) {
  window.addEventListener('scroll', () => { btt.style.display = window.scrollY > 300 ? 'flex' : 'none'; });
  btt.addEventListener('click', e => { e.preventDefault(); window.scrollTo({top: 0, behavior: 'smooth'}); });
}

// Confirmation avant une action definitive signalee par data-confirm.
document.querySelectorAll('form[data-confirm]').forEach(form => {
  form.addEventListener('submit', event => {
    const message = form.getAttribute('data-confirm');
    if (message && !window.confirm(message)) {
      event.preventDefault();
    }
  });
});
