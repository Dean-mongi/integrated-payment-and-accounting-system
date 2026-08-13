import './bootstrap';

const shell = document.querySelector('[data-app-shell]');
const sidebar = document.querySelector('[data-sidebar]');
const backdrop = document.querySelector('[data-drawer-backdrop]');
const opener = document.querySelector('[data-drawer-open]');

const closeDrawer = () => {
    shell?.classList.remove('drawer-open');
    backdrop?.setAttribute('hidden', '');
    opener?.setAttribute('aria-expanded', 'false');
};

document.querySelector('[data-drawer-open]')?.addEventListener('click', () => {
    shell?.classList.add('drawer-open');
    backdrop?.removeAttribute('hidden');
    opener?.setAttribute('aria-expanded', 'true');
    sidebar?.querySelector('a, button')?.focus();
});
document.querySelector('[data-drawer-close]')?.addEventListener('click', closeDrawer);
backdrop?.addEventListener('click', closeDrawer);
document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeDrawer(); });

const themeToggle = document.querySelector('[data-theme-toggle]');
const savedTheme = localStorage.getItem('ledger-theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
document.documentElement.dataset.theme = savedTheme;
document.body.dataset.theme = savedTheme;
themeToggle?.setAttribute('aria-pressed', String(savedTheme === 'light'));
themeToggle?.addEventListener('click', () => {
    const theme = document.body.dataset.theme === 'light' ? 'dark' : 'light';
    document.documentElement.dataset.theme = theme;
    document.body.dataset.theme = theme;
    themeToggle.setAttribute('aria-pressed', String(theme === 'light'));
    localStorage.setItem('ledger-theme', theme);
});

const shell = document.querySelector('[data-app-shell]');
const sidebar = document.querySelector('[data-sidebar]');
const backdrop = document.querySelector('[data-drawer-backdrop]');
const opener = document.querySelector('[data-drawer-open]');

const closeDrawer = () => {
    shell?.classList.remove('drawer-open');
    backdrop?.setAttribute('hidden', '');
    opener?.setAttribute('aria-expanded', 'false');
};

document.querySelector('[data-drawer-open]')?.addEventListener('click', () => {
    shell?.classList.add('drawer-open');
    backdrop?.removeAttribute('hidden');
    opener?.setAttribute('aria-expanded', 'true');
    sidebar?.querySelector('a, button')?.focus();
});
document.querySelector('[data-drawer-close]')?.addEventListener('click', closeDrawer);
backdrop?.addEventListener('click', closeDrawer);
document.addEventListener('keydown', (event) => { if (event.key === 'Escape') closeDrawer(); });

const themeToggle = document.querySelector('[data-theme-toggle]');
const savedTheme = localStorage.getItem('ledger-theme') || (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
document.documentElement.dataset.theme = savedTheme;
document.body.dataset.theme = savedTheme;
themeToggle?.setAttribute('aria-pressed', String(savedTheme === 'light'));
themeToggle?.addEventListener('click', () => {
    const theme = document.body.dataset.theme === 'light' ? 'dark' : 'light';
    document.documentElement.dataset.theme = theme;
    document.body.dataset.theme = theme;
    themeToggle.setAttribute('aria-pressed', String(theme === 'light'));
    localStorage.setItem('ledger-theme', theme);
});
