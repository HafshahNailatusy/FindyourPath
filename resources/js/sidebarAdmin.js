document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('fypSidebar');
  const backdrop = document.getElementById('sidebarBackdrop');
  const closeBtn = document.getElementById('sidebarCloseBtn');
  const toggleBtn = document.getElementById('sidebarToggleBtn');

  function setSidebar(isOpen) {
    if (sidebar) {
      sidebar.classList.toggle('open', isOpen);
      sidebar.classList.toggle('active', isOpen);
    }
    if (backdrop) {
      backdrop.classList.toggle('show', isOpen);
      backdrop.classList.toggle('active', isOpen);
    }
  }

  toggleBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    setSidebar(true);
  });
  closeBtn?.addEventListener('click', () => setSidebar(false));
  backdrop?.addEventListener('click', () => setSidebar(false));
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') setSidebar(false);
  });
});
