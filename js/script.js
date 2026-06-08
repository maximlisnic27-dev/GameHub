
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
    hero_my_collection: 'Colecția mea →',
    about_title: 'Ce este GameVault?',
    about_text: 'GameVault este partenerul tău personal pentru monitorizarea jocurilor. Organizează-ți colecția, înregistrează orele jucate, urmărește statusul fiecărui titlu și lasă recenzii personale — totul salvat local în fișiere JSON. Fără cloud, fără bază de date, doar al tău.',
    label_features: '// funcționalități',
    features_title: 'Tot ce ai nevoie',
    f_card1_title: 'Colecție Personală', f_card1_desc: 'Adaugă orice joc cu titlu, platformă, gen și propria notă.',
    f_card2_title: 'Urmărire Status', f_card2_desc: 'Marchează jocurile ca În desfășurare, Finalizate, Backlog sau Abandonate.',
    f_card3_title: 'Istoric Timp de Joc', f_card3_desc: 'Ține evidența orelor pe care le investești în statisticile tale de gaming.',
    f_card4_title: 'Sistem de Note', f_card4_desc: 'Evaluează fiecare joc de la 1 la 5 stele și scrie o scurtă recenzie personală.',
    f_card5_title: 'Mod Întunecat / Luminos', f_card5_desc: 'Interfață optimizată atât pentru sesiuni nocturne, cât și pentru lumina zilei.',
    f_card6_title: 'Complet Responsiv', f_card6_desc: 'Funcționează perfect pe PC, tabletă și mobil.',
    label_ready: '// gata?',
    cta_title: 'Pregătit <span class="text-gradient">Player</span> One?',
    cta_sub: 'Creează-ți contul și adaugă primul tău joc în mai puțin de 60 de secunde.',
    cta_open_vault: 'DESCHIDE SEIFUL →',
    cta_create_account: 'CREEAZĂ CONT →',
    footer_copy:'© 2025 GameVault. Toate drepturile rezervate.'
  },
  en: {
    nav_home:'Home', nav_about:'About', nav_features:'Features',
    nav_dashboard:'Collection', nav_contact:'Contact',
    nav_login:'Login', nav_register:'Register', nav_logout:'Logout',
    hero_eyebrow:'Your game collection',
    hero_title:'Manage your <span class="accent">gaming</span> <span class="accent2">library</span>',
    hero_sub:'Add games, track progress, rate your experience. All in one place.',
    hero_start:'Start Game', hero_demo:'Explore',
    hero_my_collection: 'My Collection →',
    about_title: 'What is GameVault?',
    about_text: 'GameVault is your personal game tracking companion. Organize your collection, log hours played, track status per title, and leave personal reviews — all stored locally in JSON files. No cloud, no database, just yours.',
    label_features: '// features',
    features_title: 'Everything you need',
    f_card1_title: 'Personal Collection', f_card1_desc: 'Add any game with title, platform, genre, and your own rating.',
    f_card2_title: 'Status Tracking', f_card2_desc: 'Mark games as Playing, Completed, Backlog, or Dropped.',
    f_card3_title: 'Playtime Logging', f_card3_desc: 'Keep track of how many hours you invest in your gaming stats.',
    f_card4_title: 'Rating System', f_card4_desc: 'Rate each game 1–5 stars and write a short personal review.',
    f_card5_title: 'Dark / Light Mode', f_card5_desc: 'UI optimized for both late-night sessions and daylight.',
    f_card6_title: 'Fully Responsive', f_card6_desc: 'Works seamlessly on PC, tablet and mobile.',
    label_ready: '// ready?',
    cta_title: 'Ready <span class="text-gradient">Player</span> One?',
    cta_sub: 'Create your account and add your first game in under 60 seconds.',
    cta_open_vault: 'OPEN VAULT →',
    cta_create_account: 'CREATE ACCOUNT →',
    footer_copy:'© 2025 GameVault. All rights reserved.'
  },
  ru: {
    nav_home:'Главная', nav_about:'О нас', nav_features:'Функции',
    nav_dashboard:'Коллекция', nav_contact:'Контакт',
    nav_login:'Войти', nav_register:'Регистрация', nav_logout:'Выход',
    hero_eyebrow:'Твоя коллекция игр',
    hero_title:'Управляй своей <span class="accent">игровой</span> <span class="accent2">библиотекой</span>',
    hero_sub:'Добавляй игры, следи за прогрессом, оценивай опыт. Всё в одном месте.',
    hero_start:'Начать', hero_demo:'Обзор',
    hero_my_collection: 'Моя коллекция →',
    about_title: 'Что такое GameVault?',
    about_text: 'GameVault — это ваш личный компаньон для отслеживания игр. Организуйте свою коллекцию, записывайте сыгранные часы, следите за статусом каждой игры и оставляйте личные отзывы — всё это хранится локально в файлах JSON. Без облака, без базы данных, только ваше.',
    label_features: '// функции',
    features_title: 'Всё, что вам нужно',
    f_card1_title: 'Личная коллекция', f_card1_desc: 'Добавляйте любую игру с названием, платформой, жанром и собственной оценкой.',
    f_card2_title: 'Отслеживание статуса', f_card2_desc: 'Отмечайте игры как В процессе, Завершено, Бэклог или Брошено.',
    f_card3_title: 'Учет игрового времени', f_card3_desc: 'Следите за количеством часов, которые вы инвестируете в свою игровую статистику.',
    f_card4_title: 'Система рейтингов', f_card4_desc: 'Оценивайте каждую игру от 1 до 5 звезд и пишите краткий личный отзыв.',
    f_card5_title: 'Темный / Светлый режим', f_card5_desc: 'Интерфейс оптимизирован как для ночных сессий, так и для дневного света.',
    f_card6_title: 'Полностью адаптивный', f_card6_desc: 'Прогресс доступен на ПК, планшетах и мобильных устройствах.',
    label_ready: '// готовы?',
    cta_title: 'Готов, <span class="text-gradient">Первый</span> игрок?',
    cta_sub: 'Создайте учетную запись и добавьте свою первую игру менее чем за 60 секунд.',
    cta_open_vault: 'ОТКРЫТЬ ХРАНИЛИЩЕ →',
    cta_create_account: 'СОЗДАТЬ АККАУНТ →',
    footer_copy:'© 2025 GameVault. Все права защищены.'
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
