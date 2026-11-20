document.addEventListener("DOMContentLoaded", () => {
    const tabButtons = document.querySelectorAll(".tab-btn");
    const tabContents = document.querySelectorAll(".tab-content");

    function switchTab(target) {
        if (!target) return;
        const targetBtn = document.querySelector(`.tab-btn[data-tab="${target}"]`);
        const targetContent = document.getElementById(`tab-${target}`);
        if (!targetBtn || !targetContent) return;

        tabButtons.forEach((btn) => btn.classList.remove("active"));
        tabContents.forEach((content) => content.classList.remove("active"));

        targetBtn.classList.add("active");
        targetContent.classList.add("active");

        const url = new URL(window.location);
        url.searchParams.set("tab", target);
        window.history.replaceState({}, "", url);
    }

    tabButtons.forEach((button) => {
        button.addEventListener("click", () => {
            switchTab(button.dataset.tab);
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get("tab");
    if (tabParam) {
        switchTab(tabParam);
    }

    const levelOptions = {
        "": "— Pilih level —",
        Beginner: "Beginner",
        Intermediate: "Intermediate",
        Advanced: "Advanced",
        Expert: "Expert",
    };

    const fields = {
        goals: [
            {
                name: "title",
                label: "Nama Target / Skill",
                type: "text",
                required: true,
            },
            {
                name: "category",
                label: "Jenis Data",
                type: "select",
                options: {
                    "": "— Pilih jenis —",
                    Goal: "Goal / Target",
                    Skill: "Skill / Kemampuan",
                },
            },
            {
                name: "current_level",
                label: "Level Saat Ini",
                type: "select",
                options: levelOptions,
            },
            {
                name: "target_level",
                label: "Level Target",
                type: "select",
                options: levelOptions,
            },
            {
                name: "progress",
                label: "Progress (0-100%)",
                type: "number",
                min: 0,
                max: 100,
                defaultValue: 0,
            },
            { name: "target_date", label: "Target Tanggal", type: "date" },
            { name: "description", label: "Deskripsi", type: "textarea" },
        ],

        projects: [
            {
                name: "project_name",
                label: "Nama Proyek",
                type: "text",
                required: true,
            },
            { name: "category", label: "Kategori", type: "text" },
            {
                name: "technologies",
                label: "Teknologi yang Dipakai",
                type: "text",
                hint: "Pisahkan dengan koma, contoh: Laravel, MySQL, Tailwind",
            },
            { name: "project_url", label: "Link Proyek", type: "url" },
            {
                name: "description",
                label: "Deskripsi Proyek",
                type: "textarea",
            },
            {
                name: "status",
                label: "Status",
                type: "select",
                options: {
                    Planned: "Direncanakan",
                    "In Progress": "Sedang Berjalan",
                    Completed: "Selesai",
                },
                defaultValue: "In Progress",
            },
        ],

        achievements: [
            {
                name: "title",
                label: "Nama Pencapaian",
                type: "text",
                required: true,
            },
            {
                name: "category",
                label: "Kategori",
                type: "text",
                hint: "Contoh: Lomba, Sertifikasi, Organisasi",
            },
            {
                name: "organizer",
                label: "Penyelenggara / Pemberi Penghargaan",
                type: "text",
            },
            {
                name: "achievement_date",
                label: "Tanggal Pencapaian",
                type: "date",
            },
            { name: "description", label: "Deskripsi", type: "textarea" },
            {
                name: "certificate_file",
                label: "File Sertifikat / Bukti",
                type: "file",
                accept: ".pdf,.jpg,.jpeg,.png",
                hint: "PDF, JPG, atau PNG. Maksimal 2 MB.",
            },
        ],

        career: [
            {
                name: "career_title",
                label: "Karier Impian",
                type: "text",
                required: true,
            },
            {
                name: "description",
                label: "Deskripsi / Langkah untuk Mencapai Karier",
                type: "textarea",
            },
            { name: "target_date", label: "Target Tanggal", type: "date" },
            {
                name: "status",
                label: "Status",
                type: "select",
                options: {
                    Planning: "Direncanakan",
                    "In Progress": "Sedang Berjalan",
                    Completed: "Selesai",
                },
            },
        ],
    };

    const modal = document.getElementById("recordModal");
    const form = document.getElementById("recordForm");
    const formFields = document.getElementById("formFields");
    const formMethod = document.getElementById("formMethod");
    const modalTitle = document.getElementById("modalTitle");

    function generateForm(type, record = {}) {
        formFields.innerHTML = "";

        fields[type].forEach((field) => {
            const group = document.createElement("div");
            group.className = "form-group";

            const label = document.createElement("label");
            label.textContent = field.label;
            label.htmlFor = field.name;

            let input;

            if (field.type === "textarea") {
                input = document.createElement("textarea");
            } else if (field.type === "select") {
                input = document.createElement("select");

                Object.entries(field.options).forEach(([value, text]) => {
                    const option = document.createElement("option");
                    option.value = value;
                    option.textContent = text;
                    input.appendChild(option);
                });
            } else {
                input = document.createElement("input");
                input.type = field.type;
            }

            input.name = field.name;
            input.id = field.name;

            if (field.required) input.required = true;
            if (field.accept) input.accept = field.accept;
            if (field.min !== undefined) input.min = field.min;
            if (field.max !== undefined) input.max = field.max;

            if (field.type !== "file") {
                if (record[field.name] != null && record[field.name] !== "") {
                    input.value = record[field.name];
                } else if (field.defaultValue !== undefined) {
                    input.value = field.defaultValue;
                } else if (field.type === "select") {
                    input.value = Object.keys(field.options)[0];
                }
            }

            group.appendChild(label);
            group.appendChild(input);

            let hint = field.hint || "";
            if (field.type === "file" && record.certificate_url) {
                hint =
                    "File saat ini sudah ada. Pilih file baru hanya jika ingin menggantinya. " +
                    hint;
            }
            if (hint) {
                const small = document.createElement("small");
                small.className = "field-hint";
                small.textContent = hint;
                group.appendChild(small);
            }

            formFields.appendChild(group);
        });
    }

    function openModal(type, action, method, record = {}) {
        if (!fields[type]) return;

        form.reset();
        if (action) {
            form.action = action;
        }
        formMethod.value = "POST";

        document.getElementById("formType").value = type;
        document.getElementById("formAction").value =
            method === "POST" ? "create" : "update";
        document.getElementById("formId").value = record.id || "";

        modalTitle.textContent =
            method === "POST" ? "Tambah Data Baru" : "Edit Data";

        generateForm(type, record);

        modal.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        modal.classList.remove("show");
        document.body.style.overflow = "";
        form.reset();
    }

    document.querySelectorAll("[data-create]").forEach((button) => {
        button.addEventListener("click", () => {
            openModal(
                button.dataset.create,
                button.dataset.action || form.action,
                "POST",
            );
        });
    });

    document.querySelectorAll("[data-edit]").forEach((button) => {
        button.addEventListener("click", () => {
            try {
                const record = JSON.parse(button.dataset.record);

                openModal(
                    button.dataset.edit,
                    button.dataset.action,
                    "PUT",
                    record,
                );
            } catch (error) {
                alert("Data gagal dibaca.");
            }
        });
    });

    document.getElementById("closeModal").addEventListener("click", closeModal);

    document
        .getElementById("cancelModal")
        .addEventListener("click", closeModal);

    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeModal();
        }
    });

    const reopen = window.opportunityReopen;
    if (reopen) {
        openModal(
            reopen.type,
            reopen.action,
            reopen.method,
            reopen.record || {},
        );
    }

    const errorMsg = document.body.dataset.error;
    if (errorMsg) {
        alert("Gagal menyimpan: " + errorMsg);
    }
});
