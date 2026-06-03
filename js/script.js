
const THEME_KEY = 'gv_theme';

function initTheme() {
  const saved = localStorage.getItem(THEME_KEY) || 'dark';
  document.documentElement.setAttribute('data-theme', saved);
  updateThemeBtn(saved);
}
function toggleTheme() {
  const cur  = document.documentElement.getAttribute('data-theme');
  const next = cur === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  localStorage.setItem(THEME_KEY, next);
  updateThemeBtn(next);
}
function updateThemeBtn(theme) {
  const btn = document.getElementById('themeToggle');
  if (btn) btn.textContent = theme === 'dark' ? '☀️' : '🌙';
}

const LANG_KEY = 'gv_lang';

const translations = {
  ro: {
    nav_home:'Acasă', nav_about:'Despre', nav_features:'Funcționalități',
    nav_dashboard:'Colecție', nav_contact:'Contact',
    nav_login:'Autentificare', nav_register:'Înregistrare', nav_logout:'Ieșire',
    hero_eyebrow:'Colecția ta de jocuri',
    hero_title:'Gestionează-ți <span class="accent">biblioteca</span> <span class="accent2">gaming</span>',
    hero_sub:'Adaugă jocuri, urmărește progresul, evaluează experiența. Totul într-un singur loc.',
    hero_start:'Start Game', hero_demo:'Explorează',
    footer_copy:'© 2025 GameVault. Toate drepturile rezervate.',
  },
  en: {
    nav_home:'Home', nav_about:'About', nav_features:'Features',
    nav_dashboard:'Collection', nav_contact:'Contact',
    nav_login:'Login', nav_register:'Register', nav_logout:'Logout',
    hero_eyebrow:'Your game collection',
    hero_title:'Manage your <span class="accent">gaming</span> <span class="accent2">library</span>',
    hero_sub:'Add games, track progress, rate your experience. All in one place.',
    hero_start:'Start Game', hero_demo:'Explore',
    footer_copy:'© 2025 GameVault. All rights reserved.',
  },
  ru: {
    nav_home:'Главная', nav_about:'О нас', nav_features:'Функции',
    nav_dashboard:'Коллекция', nav_contact:'Контакт',
    nav_login:'Войти', nav_register:'Регистрация', nav_logout:'Выход',
    hero_eyebrow:'Твоя коллекция игр',
    hero_title:'Управляй своей <span class="accent">игровой</span> <span class="accent2">библиотекой</span>',
    hero_sub:'Добавляй игры, следи за прогрессом, оценивай опыт. Всё в одном месте.',
    hero_start:'Начать', hero_demo:'Обзор',
    footer_copy:'© 2025 GameVault. Все права защищены.',
  }
};

function initLang() {
  const saved = localStorage.getItem(LANG_KEY) || 'ro';
  const sel = document.getElementById('langSelect');
  if (sel) sel.value = saved;
  applyLang(saved);
}
function applyLang(lang) {
  const t = translations[lang];
  if (!t) return;
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (t[key] !== undefined) el.innerHTML = t[key];
  });
  localStorage.setItem(LANG_KEY, lang);
}

function initHamburger() {
  const btn  = document.getElementById('hamburgerBtn');
  const menu = document.getElementById('mobileMenu');
  if (!btn || !menu) return;
  btn.addEventListener('click', () => {
    btn.classList.toggle('open');
    menu.classList.toggle('open');
  });
  menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
    btn.classList.remove('open'); menu.classList.remove('open');
  }));
}

function showAlert(containerId, message, type = 'info') {
  const c = document.getElementById(containerId);
  if (!c) return;
  const icons = { success:'✓', error:'✕', info:'>' };
  c.innerHTML = `<div class="alert alert-${type}"><span>${icons[type]}</span>${message}</div>`;
  setTimeout(() => { if (c) c.innerHTML = ''; }, 4000);
}

function openModal(id)  { const m = document.getElementById(id); if(m) m.classList.add('open'); }
function closeModal(id) { const m = document.getElementById(id); if(m) m.classList.remove('open'); }

function starsHtml(r) { return '★'.repeat(+r||0) + '☆'.repeat(5-(+r||0)); }

document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initLang();
  initHamburger();

  const themeBtn = document.getElementById('themeToggle');
  if (themeBtn) themeBtn.addEventListener('click', toggleTheme);

  const langSel = document.getElementById('langSelect');
  if (langSel) langSel.addEventListener('change', e => applyLang(e.target.value));

  document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
  });

  const cur = location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('.navbar-links a, .mobile-menu a').forEach(a => {
    if (a.getAttribute('href') === cur) a.classList.add('active');
  });
});
