import './bootstrap';
import * as bootstrap from 'bootstrap';
import './agua/admin';
import './agua/padron';
import './agua/cobros';
import './agua/reportes';
import './agua/portal';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {
    initSidebarToggle();
    initPasswordToggles();
    initTooltips();
});

function initSidebarToggle() {
    const toggle = document.querySelector('[data-agua-sidebar-toggle]');
    const sidebar = document.querySelector('[data-agua-sidebar]');
    const backdrop = document.querySelector('[data-agua-sidebar-backdrop]');

    if (!toggle || !sidebar || !backdrop) {
        return;
    }

    const open = () => {
        sidebar.classList.add('is-open');
        backdrop.classList.add('is-open');
    };

    const close = () => {
        sidebar.classList.remove('is-open');
        backdrop.classList.remove('is-open');
    };

    toggle.addEventListener('click', open);
    backdrop.addEventListener('click', close);

    sidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', close));
}

function initPasswordToggles() {
    document.querySelectorAll('[data-agua-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetSelector = button.getAttribute('data-agua-password-toggle');
            const input = targetSelector ? document.querySelector(targetSelector) : null;
            const icon = button.querySelector('.material-symbols-outlined');

            if (!input) {
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                if (icon) icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                if (icon) icon.textContent = 'visibility';
            }
        });
    });
}

function initTooltips() {
    document
        .querySelectorAll('[data-bs-toggle="tooltip"]')
        .forEach((el) => new bootstrap.Tooltip(el));
}
