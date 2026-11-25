document.addEventListener("DOMContentLoaded", function () {
    // Pencarian dan Filter Tabel
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const dateFilter = document.getElementById('dateFilter');
    const tableRows = document.querySelectorAll('#announcementTable tbody tr');

    function applyFilters() {
        const keyword = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const categoryVal = categoryFilter ? categoryFilter.value : '';
        const dateVal = dateFilter ? dateFilter.value : '';

        const now = new Date();

        tableRows.forEach(row => {
            const titleCell = row.querySelector('.user-name');
            const contentCell = row.querySelector('.announcement-content-cell');
            const categoryCell = row.querySelector('.badge');
            const dateCell = row.querySelector('.reg-date');

            if (!titleCell) return;

            const titleText = titleCell.textContent.toLowerCase();
            const contentText = contentCell ? contentCell.textContent.toLowerCase() : '';
            const categoryText = categoryCell ? categoryCell.textContent.trim() : '';
            const rawDate = dateCell ? dateCell.dataset.rawDate : '';

            // Pencarian 
            const matchSearch = keyword === '' || titleText.includes(keyword) || contentText.includes(keyword);

            // Filter Kategori
            const matchCategory = categoryVal === '' || categoryText === categoryVal;

            // Filter Hari
            let matchDate = true;
            if (dateVal !== '' && rawDate !== '') {
                const days = parseInt(dateVal, 10);
                if (!isNaN(days)) {
                    const rowDate = new Date(rawDate);
                    const diffTime = Math.abs(now - rowDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    matchDate = diffDays <= days;
                }
            }

            row.style.display = (matchSearch && matchCategory && matchDate) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (categoryFilter) categoryFilter.addEventListener('change', applyFilters);
    if (dateFilter) dateFilter.addEventListener('change', applyFilters);

    // Ekspor CSV
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            let csv = [];
            let rows = document.querySelectorAll("#announcementTable tr");

            for (let i = 0; i < rows.length; i++) {
                if (rows[i].style.display === 'none') continue;
                let row = [], cols = rows[i].querySelectorAll("td, th");
                for (let j = 0; j < cols.length - 1; j++) {
                    let text = cols[j].innerText.replace(/\n/g, " ").trim();
                    row.push('"' + text.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }

            let csvFile = new Blob([csv.join("\n")], { type: "text/csv;charset=utf-8;" });
            let downloadLink = document.createElement("a");
            downloadLink.download = "Data_Announcement_FindYourPath.csv";
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        });
    }

    // Tambah & Edit Announcement
    const announcementModal = document.getElementById('announcementModal');
    const announcementForm = document.getElementById('announcementForm');
    const openAddModalBtn = document.getElementById('openAddModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelModalBtn = document.getElementById('cancelModalBtn');

    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');
    const formActionInput = document.getElementById('formActionInput');
    const inputTitle = document.getElementById('inputTitle');
    const inputCategory = document.getElementById('inputCategory');
    const inputContent = document.getElementById('inputContent');
    const inputTanggal = document.getElementById('inputTanggal');
    const submitModalBtn = document.getElementById('submitModalBtn');

    function getFormattedToday() {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const now = new Date();
        const day = String(now.getDate()).padStart(2, '0');
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        return `${day} ${month} ${year}`;
    }

    function openModal() {
        if (announcementModal) announcementModal.classList.add('active');
    }

    function closeModal() {
        if (announcementModal) announcementModal.classList.remove('active');
    }

    if (openAddModalBtn) {
        openAddModalBtn.addEventListener('click', function () {
            const targetUrl = typeof storeAnnouncementUrl !== 'undefined' ? storeAnnouncementUrl : '/admin/announcements';
            modalTitle.textContent = 'Tambah Pengumuman';
            announcementForm.action = targetUrl;
            if (formActionInput) formActionInput.value = targetUrl;
            formMethod.value = 'POST';
            inputTitle.value = '';
            inputCategory.value = 'Umum';
            inputContent.value = '';
            inputTanggal.value = getFormattedToday();
            submitModalBtn.textContent = 'Publikasikan';
            openModal();
        });
    }

    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);
    if (announcementModal) {
        announcementModal.addEventListener('click', function (e) {
            if (e.target === announcementModal) closeModal();
        });
    }

    // Dropdown 
    const editTriggers = document.querySelectorAll('.edit-menu-trigger');
    editTriggers.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const currentMenu = this.nextElementSibling;
            const isOpen = currentMenu.classList.contains('show');

            document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });

            if (!isOpen) {
                const rect = this.getBoundingClientRect();
                currentMenu.style.position = 'fixed';
                currentMenu.style.top = `${rect.bottom + 4}px`;
                currentMenu.style.left = `${Math.max(12, rect.right - 160)}px`;
                currentMenu.style.right = 'auto';
                currentMenu.style.zIndex = '1500';
                currentMenu.classList.add('show');
            }
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    });

    window.addEventListener('scroll', function () {
        document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }, true);

    // Tombol Edit di Dropdown
    const editButtons = document.querySelectorAll('.dropdown-item--edit');
    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            modalTitle.textContent = 'Perbarui Pengumuman';
            announcementForm.action = this.dataset.updateUrl;
            if (formActionInput) formActionInput.value = this.dataset.updateUrl;
            formMethod.value = 'PUT';
            inputTitle.value = this.dataset.title;
            inputCategory.value = this.dataset.category;
            inputContent.value = this.dataset.content;
            inputTanggal.value = this.dataset.date;
            submitModalBtn.textContent = 'Simpan Perubahan';

            this.closest('.action-dropdown-menu').classList.remove('show');
            openModal();
        });
    });

    // Tombol Notifikasi Header
    const notifBtn = document.querySelector('.header-notif');
    if (notifBtn) {
        notifBtn.addEventListener('click', function () {
            alert('Belum ada notifikasi baru.');
        });
    }
});