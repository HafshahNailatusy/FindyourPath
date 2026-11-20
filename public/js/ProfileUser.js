
document.addEventListener('DOMContentLoaded', function () {

    // FUNGSI BUKA / TUTUP ELEMEN
    function toggleElement(element) {
        if (!element) return;

        const currentlyHidden =
            element.classList.contains('hidden') ||
            element.hasAttribute('hidden');

        if (currentlyHidden) {
            element.classList.remove('hidden');
            element.removeAttribute('hidden');
        } else {
            element.classList.add('hidden');
            element.setAttribute('hidden', '');
        }
    }

    // EDIT PROFILE
    window.toggleEdit = function () {
        toggleElement(document.getElementById('editProfile'));
    };

    // TAMBAH KEAHLIAN
    window.toggleSkillForm = function () {
        toggleElement(document.getElementById('addSkillForm'));
    };

    // BUKA MODAL AKADEMIK
    window.openAcademicModal = function () {
        const modal = document.getElementById('academicModal');

        if (!modal) return;

        modal.classList.remove('hidden');
        modal.removeAttribute('hidden');
    };

    // TUTUP MODAL AKADEMIK
    window.closeAcademicModal = function () {
        const modal = document.getElementById('academicModal');

        if (!modal) return;

        modal.classList.add('hidden');
        modal.setAttribute('hidden', '');
    };

    // KLIK DI LUAR MODAL
    const academicModal = document.getElementById('academicModal');

    if (academicModal) {
        academicModal.addEventListener('click', function (event) {
            if (event.target === academicModal) {
                window.closeAcademicModal();
            }
        });
    }

    // KONFIRMASI HAPUS KEAHLIAN
    document.querySelectorAll('.delete-skill-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const confirmation = confirm(
                'Yakin ingin menghapus keahlian ini?'
            );

            if (!confirmation) {
                event.preventDefault();
            }
        });
    });

});