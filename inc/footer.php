  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Make all tables horizontally scrollable on small screens
      const tables = document.querySelectorAll('main table, .container table, .content table, .main-content table, table');
      tables.forEach(function(tbl) {
        if (!tbl.parentElement || tbl.parentElement.classList.contains('table-responsive-wrapper')) return;
        const wrapper = document.createElement('div');
        wrapper.className = 'table-responsive-wrapper';
        wrapper.style.overflowX = 'auto';
        wrapper.style.webkitOverflowScrolling = 'touch';
        tbl.style.width = '100%';
        tbl.parentNode.insertBefore(wrapper, tbl);
        wrapper.appendChild(tbl);
      });

      // Auto-close mobile navbar after clicking a link
      const navCollapse = document.getElementById('navbarNav');
      if (navCollapse) {
        navCollapse.querySelectorAll('a.nav-link').forEach(function(link) {
          link.addEventListener('click', function() {
            const bsCollapse = bootstrap.Collapse.getInstance(navCollapse) || new bootstrap.Collapse(navCollapse, { toggle: false });
            if (window.innerWidth < 992) {
              bsCollapse.hide();
            }
          });
        });
      }
    });
  </script>
</body>
</html>
