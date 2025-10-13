        const sidebar = document.getElementById('sidebar');
        const toggleSidebarBtn = document.getElementById('toggleSidebar');

        toggleSidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        function previewProfileImage(input) {
            const preview = document.getElementById('profile-preview');
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(file);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const openPanel = json(session('openPanel'));
            if (openPanel) {
                showPanel(openPanel);
            }

            const successAlert = document.getElementById('alertSuccess');
            const errorAlert = document.getElementById('alertError');
            [successAlert, errorAlert].forEach(alert => {
                if (alert) {
                    setTimeout(() => {
                        alert.classList.add('opacity-0');
                        setTimeout(() => alert.remove(), 1000);
                    }, 3000);
                }
            });
        });

        function showPanel(panelId) {
            document.querySelectorAll('.panel').forEach(p => p.classList.add('hidden'));
            document.getElementById(panelId).classList.remove('hidden');

            document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('bg-[#E17C5F]', 'text-white'));
            const activeBtn = document.getElementById(`btn-${panelId}`);
            if (activeBtn) activeBtn.classList.add('bg-[#E17C5F]', 'text-white');

            if (window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
            }
        }

        let deleteFormToSubmit = null;

        function showDeleteModal(event) {
            event.preventDefault();
            deleteFormToSubmit = event.target;
            const userName = deleteFormToSubmit.dataset.name || 'this account';
            document.getElementById('deleteTargetName').textContent = userName;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex', 'items-center', 'justify-center');
            return false;
        }

        function closeDeleteModal() {
            deleteFormToSubmit = null;
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function submitDeleteForm() {
            if (deleteFormToSubmit) {
                deleteFormToSubmit.submit();
            }
        }

        function toggleImportForm() {
            const form = document.getElementById('importStudentForm');
            form.classList.toggle('hidden');
        }

        function toggleImportFacultyForm() {
            const form = document.getElementById('importFacultyForm');
            form.classList.toggle('hidden');
        }

        function filterTableRows(inputId, rowSelector) {
            const input = document.getElementById(inputId);
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll(rowSelector);
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(filter) ? '' : 'none';
            });
        }