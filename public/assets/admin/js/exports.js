document.addEventListener('DOMContentLoaded', function() {
    initializeExportButtons();
    initializeFormatButtons();
});

function initializeExportButtons() {
    document.querySelectorAll('.export-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            const card = this.closest('.card');
            const activeFormat = card.querySelector('.format-btn.active');
            const format = activeFormat ? activeFormat.dataset.format : 'csv';
            
            exportData(type, format);
        });
    });
}

function initializeFormatButtons() {
    document.querySelectorAll('.format-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const parent = this.parentElement;
            parent.querySelectorAll('.format-btn').forEach(b => {
                b.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
}

function exportData(type, format) {
    showExportLoading();

    const url = getExportUrl(type);
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.style.display = 'none';

    const csrfToken = document.createElement('input');
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrfToken);

    const formatInput = document.createElement('input');
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);

    document.body.appendChild(form);
    form.submit();

    setTimeout(hideExportLoading, 1000);
}

function getExportUrl(type) {
    const urls = {
        'reports': '/admin/exports/reports',
        'ratings': '/admin/exports/ratings',
        'units': '/admin/exports/units',
        'unit-types': '/admin/exports/unit-types'
    };
    return urls[type] || urls.reports;
}

function showExportLoading() {
    hideExportLoading();

    const overlay = document.createElement('div');
    overlay.id = 'export-loading-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(2px);
    `;

    const modal = document.createElement('div');
    modal.style.cssText = `
        background: white;
        padding: 40px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        max-width: 400px;
        width: 90%;
        animation: fadeIn 0.3s ease;
    `;

    const spinner = document.createElement('div');
    spinner.style.cssText = `
        width: 60px;
        height: 60px;
        border: 4px solid #f1f5f9;
        border-top: 4px solid #f8773c;
        border-radius: 50%;
        margin: 0 auto 25px;
        animation: spin 1s linear infinite;
    `;

    const title = document.createElement('h5');
    title.style.cssText = `
        color: #1e293b;
        margin: 0 0 10px;
        font-weight: 600;
        font-size: 18px;
    `;
    title.textContent = 'Exporting Data';

    const message = document.createElement('p');
    message.style.cssText = `
        color: #64748b;
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
    `;
    message.textContent = 'Preparing your download...';

    modal.appendChild(spinner);
    modal.appendChild(title);
    modal.appendChild(message);
    overlay.appendChild(modal);
    document.body.appendChild(overlay);

    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
}

function hideExportLoading() {
    const overlay = document.getElementById('export-loading-overlay');
    if (overlay) {
        overlay.remove();
    }
}