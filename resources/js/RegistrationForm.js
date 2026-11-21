document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".form-card");
    if (!form) return;

    const requiredFields = form.querySelectorAll(
        'input[required]:not([type="file"]), textarea[required]'
    );

    const docInput = document.getElementById("document");
    const payInput = document.getElementById("payment_receipt");

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

    function validateField(input) {
        clearError(input);

        if (!input.value.trim()) {
            const label = input.parentElement.querySelector("label");
            const name = label ? label.textContent.replace('*', '').trim() : "Kolom ini";
            showError(input, name + " wajib diisi.");
            return false;
        }

        if (input.name === "phone" && !/^[0-9+\-\s()]+$/.test(input.value)) {
            showError(input, "Nomor telepon hanya boleh berisi angka dan tanda +, -, atau kurung.");
            return false;
        }

        return true;
    }

    function validateSingleFile(input, allowedExtensions, maxMb) {
        clearError(input);
        if (!input || !input.files || input.files.length === 0) {
            return true;
        }

        const file = input.files[0];
        const extension = file.name.split(".").pop().toLowerCase();

        if (!allowedExtensions.includes(extension)) {
            showError(input, `Format file harus ${allowedExtensions.join(", ").toUpperCase()}`);
            return false;
        }

        if (file.size > maxMb * 1024 * 1024) {
            showError(input, `Ukuran file maksimal ${maxMb} MB.`);
            return false;
        }

        return true;
    }

    requiredFields.forEach(function (input) {
        input.addEventListener("input", function () {
            validateField(input);
        });
    });

    if (docInput) {
        docInput.addEventListener("change", function () {
            validateSingleFile(docInput, ["pdf", "zip", "rar"], 20);
        });
    }

    if (payInput) {
        payInput.addEventListener("change", function () {
            validateSingleFile(payInput, ["pdf", "jpg", "jpeg", "png"], 5);
        });
    }

    form.addEventListener("submit", function (e) {
        let isValid = true;

        requiredFields.forEach(function (input) {
            if (!validateField(input)) {
                isValid = false;
            }
        });

        if (docInput && !validateSingleFile(docInput, ["pdf", "zip", "rar"], 20)) {
            isValid = false;
        }

        if (payInput && !validateSingleFile(payInput, ["pdf", "jpg", "jpeg", "png"], 5)) {
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            const firstError = form.querySelector(".input-error");
            if (firstError) {
                firstError.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        }
    });
});