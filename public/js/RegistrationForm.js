
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".form-card");
    if (!form) return;

    const requiredFields = form.querySelectorAll(
        'input[required]:not([type="file"]), textarea[required]'
    );

    const fileInputs = form.querySelectorAll('input[type="file"]');

    const maxSize = 100 * 1024 * 1024;
    const allowedExtensions = ["pdf", "jpg", "jpeg", "png"];

    function getErrorElement(input) {
        let error = input.parentElement.querySelector(".client-error");

        if (!error) {
            error = document.createElement("small");
            error.className = "client-error field-error";
            error.hidden = true;
            input.insertAdjacentElement("afterend", error);
        }

        return error;
    }

    function showError(input, message) {
        const error = getErrorElement(input);
        error.textContent = message;
        error.hidden = false;
        input.classList.add("input-error");
    }

    function clearError(input) {
        const error = input.parentElement.querySelector(".client-error");

        if (error) {
            error.textContent = "";
            error.hidden = true;
        }

        input.classList.remove("input-error");
    }

    // Validasi data mahasiswa
    function validateField(input) {
        clearError(input);

        if (!input.value.trim()) {
            const label = input.parentElement.querySelector("label");
            const name = label ? label.textContent.trim() : "Kolom ini";

            showError(input, name + " wajib diisi.");
            return false;
        }

        if (input.name === "phone" && !/^[0-9+\-\s()]+$/.test(input.value)) {
            showError(input, "Nomor telepon hanya boleh berisi angka dan tanda +, -, atau kurung.");
            return false;
        }

        return true;
    }

    // Validasi file
    function validateFile(input) {
        clearError(input);

        if (!input.files || input.files.length === 0) {
            const label = input.parentElement.querySelector("label");
            const name = label ? label.textContent.trim() : "Dokumen";

            showError(input, name + " wajib diunggah.");
            return false;
        }

        const file = input.files[0];
        const extension = file.name.split(".").pop().toLowerCase();

        if (!allowedExtensions.includes(extension)) {
            showError(input, "Format file harus PDF, JPG, atau PNG.");
            return false;
        }

        if (file.size > maxSize) {
            showError(input, "Ukuran file maksimal 100 MB.");
            return false;
        }

        return true;
    }

    // Hapus pesan ketika pengguna mulai memperbaiki input
    requiredFields.forEach(function (input) {
        input.addEventListener("input", function () {
            validateField(input);
        });
    });

    fileInputs.forEach(function (input) {
        input.addEventListener("change", function () {
            if (input.files && input.files.length > 0) {
                validateFile(input);
            } else {
                clearError(input);
            }
        });
    });

    // Validasi seluruh form sebelum dikirim
    form.addEventListener("submit", function (event) {
        let firstInvalidInput = null;

        requiredFields.forEach(function (input) {
            if (!validateField(input) && !firstInvalidInput) {
                firstInvalidInput = input;
            }
        });

        fileInputs.forEach(function (input) {
            if (!validateFile(input) && !firstInvalidInput) {
                firstInvalidInput = input;
            }
        });

        if (firstInvalidInput) {
            event.preventDefault();
            firstInvalidInput.focus();
            return;
        }

        if (!confirm(
            "Pastikan semua data dan dokumen sudah benar. Lanjutkan pendaftaran?"
        )) {
            event.preventDefault();
        }
    });
});