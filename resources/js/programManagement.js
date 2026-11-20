document.addEventListener('DOMContentLoaded', function () {
    const addButton = document.getElementById('addProgramButton');
    const modal = document.getElementById('programModal');
    const closeButton = document.getElementById('closeModal');
    const cancelButton = document.getElementById('cancelModal');

    const form = document.getElementById('programForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');
    const btnSubmitModal = document.getElementById('btnSubmitModal');
    const posterPreviewBox = document.getElementById('currentPosterPreview');
    const previewImg = document.getElementById('previewImg');
    const posterHint = document.getElementById('posterHint');

    const searchInput = document.getElementById('searchProgram');
    const bidangFilter = document.getElementById('bidangFilter');

    const tableBody = document.getElementById('programTableBody');
    const resultText = document.getElementById('programResult');

    const storeUrl = form ? form.action : '';

    if (addButton) {
        addButton.addEventListener('click', function () {
            form.reset();
            form.action = storeUrl;
            methodContainer.innerHTML = ''; // Method POST
            modalTitle.textContent = 'Tambah Program';
            btnSubmitModal.textContent = 'Simpan';

            if (posterPreviewBox) posterPreviewBox.style.display = 'none';
            if (posterHint) posterHint.textContent = 'Format: JPG, PNG, WEBP (Maks. 3MB)';

            modal.classList.add('show');
        });
    }

    function closeModal() {
        if (modal) modal.classList.remove('show');
    }

    if (closeButton) closeButton.addEventListener('click', closeModal);
    if (cancelButton) cancelButton.addEventListener('click', closeModal);

    if (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) closeModal();
        });
    }

    function filterPrograms() {
        const searchValue = (searchInput.value || '').toLowerCase().trim();
        const bidangValue = bidangFilter.value;

        const rows = tableBody.querySelectorAll('tr[data-id]');
        let visible = 0;

        rows.forEach(function (row) {
            const name = (row.dataset.name || '').toLowerCase();
            const organizer = (row.dataset.organizer || '').toLowerCase();
            const bidang = row.dataset.bidang || '';

            const matchSearch =
                name.includes(searchValue) ||
                organizer.includes(searchValue);

            const matchBidang = bidangValue === '' || bidang === bidangValue;

            if (matchSearch && matchBidang) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        if (resultText) {
            resultText.textContent = 'Menampilkan ' + visible + ' dari ' + rows.length + ' program';
        }
    }

    if (searchInput) searchInput.addEventListener('input', filterPrograms);
    if (bidangFilter) bidangFilter.addEventListener('change', filterPrograms);

    if (tableBody) {
        tableBody.addEventListener('click', function (event) {
            const editButton = event.target.closest('.edit-program');
            const deleteButton = event.target.closest('.delete-program');
            const row = event.target.closest('tr[data-id]');

            if (!row) return;

            const id = row.dataset.id;
            const name = row.dataset.name || '';
            const organizer = row.dataset.organizer || '';
            const bidang = row.dataset.bidang || '';
            const desc = row.dataset.desc || '';
            const posterUrl = row.dataset.poster || '';

            if (editButton) {
                form.reset();
                form.action = storeUrl + '/' + id; // Endpoint PUT update Laravel
                methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                modalTitle.textContent = 'Edit Program';
                btnSubmitModal.textContent = 'Simpan Perubahan';

                document.getElementById('programName').value = name;
                document.getElementById('programOrganizer').value = organizer;
                document.getElementById('programBidang').value = bidang;
                document.getElementById('programDesc').value = desc;

                if (posterUrl !== '') {
                    if (previewImg) previewImg.src = posterUrl;
                    if (posterPreviewBox) posterPreviewBox.style.display = 'flex';
                    if (posterHint) posterHint.textContent = 'Pilih file baru jika ingin mengganti poster.';
                } else {
                    if (posterPreviewBox) posterPreviewBox.style.display = 'none';
                    if (posterHint) posterHint.textContent = 'Format: JPG, PNG, WEBP (Maks. 3MB)';
                }

                modal.classList.add('show');
            }

            if (deleteButton) {
                const confirmDelete = confirm(
                    'Apakah kamu yakin ingin menghapus program "' + name + '"?\nData yang dihapus tidak dapat dikembalikan.'
                );

                if (confirmDelete) {
                    const deleteForm = document.getElementById('deleteForm');
                    if (deleteForm) {
                        deleteForm.action = storeUrl + '/' + id;
                        deleteForm.submit();
                    }
                }
            }
        });
    }
});