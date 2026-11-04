/**
 * Escape HTML untuk mencegah XSS.
 */
function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (char) => {
        const entities = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        };

        return entities[char];
    });
}

/**
 * Membuat container toast jika belum tersedia.
 */
function ensureToastContainer() {
    let container = document.querySelector('.simdes-toast-container');

    if (!container) {
        container = document.createElement('div');
        container.className = 'simdes-toast-container';

        document.body.appendChild(container);
    }

    return container;
}

/**
 * Global Toast Notification.
 */
function showToast({
    title = 'Informasi Sistem',
    message = '',
    variant = 'primary',
    duration = 4200,
} = {}) {
    const container = ensureToastContainer();
    const toast = document.createElement('div');

    toast.className = 'simdes-toast';
    toast.setAttribute('role', 'status');
    toast.setAttribute('aria-live', 'polite');

    const variantMap = {
        primary: {
            color: 'var(--simdes-primary)',
            icon: 'fa-circle-info',
        },
        success: {
            color: 'var(--simdes-success)',
            icon: 'fa-circle-check',
        },
        warning: {
            color: 'var(--simdes-warning)',
            icon: 'fa-triangle-exclamation',
        },
        danger: {
            color: 'var(--simdes-danger)',
            icon: 'fa-circle-exclamation',
        },
    };

    const cfg = variantMap[variant] || variantMap.primary;

    toast.style.borderInlineStartColor = cfg.color;

    toast.innerHTML = `
        <div style="color:${cfg.color}; font-size:1.05rem; margin-top:2px;">
            <i class="fa-solid ${cfg.icon}" aria-hidden="true"></i>
        </div>

        <div style="flex:1 1 auto; min-width:0;">
            <div style="font-weight:700; font-size:0.84rem; color:var(--simdes-text);">
                ${escapeHtml(title)}
            </div>

            <div style="font-size:0.79rem; color:var(--simdes-text-muted); margin-top:2px;">
                ${escapeHtml(message)}
            </div>
        </div>

        <button
            type="button"
            class="btn-close"
            style="font-size:0.7rem;"
            aria-label="Tutup notifikasi">
        </button>
    `;

    const closeBtn = toast.querySelector('.btn-close');

    let timeout;

    const removeToast = () => {
        if (!toast.isConnected) return;

        window.clearTimeout(timeout);

        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-6px)';
        toast.style.transition = 'opacity 160ms ease, transform 160ms ease';

        window.setTimeout(() => toast.remove(), 170);
    };

    closeBtn.addEventListener('click', removeToast);

    container.appendChild(toast);

    if (duration > 0) {
        timeout = window.setTimeout(removeToast, duration);
    }
}

window.showToast = showToast;