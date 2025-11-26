(function () {
    if (window.__SKM_WIDGET_LOADED__) return;
    window.__SKM_WIDGET_LOADED__ = true;

    // Ambil script tag yang memanggil file ini
    var currentScript = document.currentScript || (function () {
        var scripts = document.getElementsByTagName('script');
        return scripts[scripts.length - 1];
    })();

    // Ambil data-* attributes dari script
    var respondenId  = currentScript.getAttribute('data-responden-id') || '';
    var opdName      = currentScript.getAttribute('data-opd-name') || '';
    var serviceName  = currentScript.getAttribute('data-service-name') || '';
    var serviceId    = currentScript.getAttribute('data-service-id') || '';

    // Base URL survey: /data-responden/{id}
    var surveyUrl = 'https://skm.tanjungpinangkota.go.id/data-responden';
    if (respondenId) {
        surveyUrl += '/' + encodeURIComponent(respondenId);
    }

    // Bangun query string dari data yang ada
    var params = [];

    if (opdName) {
        params.push('opd_name=' + encodeURIComponent(opdName));
    }

    if (serviceName) {
        params.push('service_name=' + encodeURIComponent(serviceName));
    }

    if (serviceId) {
        params.push('service_id=' + encodeURIComponent(serviceId));
    }

    if (params.length > 0) {
        surveyUrl += '?' + params.join('&');
    }

    // ==== STYLE: tombol di kanan + panel full height 70% width ====
    var style = document.createElement('style');
    style.innerHTML = `
        .skm-widget-button {
            position: fixed;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            z-index: 999999;
            background-color: #f33c33ff;
            color: #ffffff;
            border-radius: 10px 0 0 10px;
            padding: 12px 8px;
            font-size: 12px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.25);
            border: none;

            /* bikin vertikal */
            writing-mode: vertical-rl;
            text-orientation: mixed;
            letter-spacing: 1px;
            text-align: center;
        }
        .skm-widget-button svg {
            display: none; /* kalau mau tanpa icon */
        }


        .skm-widget-button svg {
            width: 16px;
            height: 16px;
        }

        .skm-widget-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.4);
            z-index: 999998;
            display: none;
        }

        .skm-widget-overlay.skm-open {
            display: block;
        }

        .skm-widget-panel {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            width: 80%;
            max-width: 100%;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            box-shadow: -10px 0 30px rgba(15, 23, 42, 0.35);
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.22, 0.61, 0.36, 1); /* <-- slide in/out */
        }

        .skm-widget-overlay.skm-open .skm-widget-panel {
            transform: translateX(0%);
        }

        .skm-widget-panel-header {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .skm-widget-panel-title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .skm-widget-panel-close {
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            padding: 4px;
            color: #6b7280;
        }

        .skm-widget-panel-body {
            flex: 1;
            background-color: #f9fafb;
        }

        .skm-widget-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        @media (max-width: 768px) {
            .skm-widget-panel {
                width: 100%;
            }
        }
    `;
    document.head.appendChild(style);

    // ==== TOMBOL NEMPEL DI KANAN ====
    var button = document.createElement('button');
    button.className = 'skm-widget-button';
    button.type = 'button';
    button.innerHTML = `
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M5 5h14v10H6l-1 4V5z" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Survey Kepuasan Masyarakat</span>
    `;

    // ==== OVERLAY + PANEL KANAN ====
    var overlay = document.createElement('div');
    overlay.className = 'skm-widget-overlay';

    var panel = document.createElement('div');
    panel.className = 'skm-widget-panel';

    var header = document.createElement('div');
    header.className = 'skm-widget-panel-header';
    header.innerHTML = `
        <div class="skm-widget-panel-title">Survey Kepuasan Masyarakat</div>
        <button class="skm-widget-panel-close" type="button" aria-label="Tutup">&times;</button>
    `;

    var body = document.createElement('div');
    body.className = 'skm-widget-panel-body';

    var iframe = document.createElement('iframe');
    iframe.className = 'skm-widget-iframe';
    iframe.src = surveyUrl;

    body.appendChild(iframe);
    panel.appendChild(header);
    panel.appendChild(body);
    overlay.appendChild(panel);

    document.body.appendChild(button);
    document.body.appendChild(overlay);

    // ==== EVENT OPEN / CLOSE ====
    function openPanel() {
        overlay.classList.add('skm-open');
    }

    function closePanel() {
        overlay.classList.remove('skm-open');
    }

    button.addEventListener('click', openPanel);

    // klik area gelap di luar panel = tutup
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) {
            closePanel();
        }
    });

    var closeBtn = header.querySelector('.skm-widget-panel-close');
    closeBtn.addEventListener('click', closePanel);
})();
