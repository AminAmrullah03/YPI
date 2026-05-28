// DIGIDAS YPI — Main JS Entry
// ApexCharts dan script lain dimuat via CDN di blade views

// CSRF token setup untuk fetch requests
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// ─── Sidebar toggle ───────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
    }

    // Auto-hide alerts
    document.querySelectorAll('.alert[data-auto-hide]').forEach(function (alert) {
        setTimeout(function () {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.4s';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    });
});
