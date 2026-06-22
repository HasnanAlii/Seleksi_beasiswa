<div x-show="toast.show" x-transition class="fixed bottom-4 right-4 z-50 rounded-xl px-4 py-3 text-sm font-semibold shadow-lg" :class="toast.type === 'error' ? 'bg-rose-600 text-white' : 'bg-emerald-600 text-white'" style="display: none;" x-text="toast.message"></div>

<script>
    function scholarshipsCrud() {
        return {
            loading: false,
            search: '',
            modalForm: false,
            modalShow: false,
            editMode: false,
            selectedItem: {},
            items: [],
            form: { scholarship_name: '', scholarship_type: '', quota: '', validity_period: '' },
            errors: {},
            toast: { show: false, message: '', type: 'success' },
            get filteredItems() {
                const keyword = this.search.toLowerCase().trim();
                if (!keyword) return this.items;
                return this.items.filter((item) =>
                    [item.scholarship_name, item.scholarship_type?.name]
                        .filter(Boolean)
                        .some((v) => String(v).toLowerCase().includes(keyword))
                );
            },
            init() { this.fetchItems(); },
            async fetchItems() {
                this.loading = true;
                try {
                    const res = await fetch('/scholarships', { headers: { Accept: 'application/json' } });
                    const result = await res.json();
                    this.items = Array.isArray(result.data) ? result.data : [];
                } catch { this.showToast('Gagal memuat data beasiswa.', 'error'); }
                finally { this.loading = false; }
            },
            openCreateModal() {
                this.editMode = false; this.selectedItem = {};
                this.form = { scholarship_name: '', scholarship_type: '', quota: '', validity_period: '' };
                this.errors = {}; this.modalForm = true;
            },
            openEditModal(item) {
                this.editMode = true; this.selectedItem = item;
                this.form = { scholarship_name: item.scholarship_name ?? '', scholarship_type: item.scholarship_type?.name ?? '', quota: item.quota ?? '', validity_period: item.validity_period ?? '' };
                this.errors = {}; this.modalForm = true;
            },
            openShowModal(item) { this.selectedItem = item; this.modalShow = true; },
            closeFormModal() { this.modalForm = false; },
            async submitForm() {
                this.errors = {};
                const isUpdate = this.editMode && this.selectedItem.id;
                const url = isUpdate ? `/scholarships/${this.selectedItem.id}` : '/scholarships';
                const method = isUpdate ? 'PUT' : 'POST';
                try {
                    const res = await fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify(this.form),
                    });
                    const result = await res.json();
                    if (!res.ok) { this.errors = result.errors ?? {}; this.showToast(result.message ?? 'Validasi gagal.', 'error'); return; }
                    this.showToast(isUpdate ? 'Beasiswa berhasil diubah.' : 'Beasiswa berhasil ditambahkan.');
                    this.modalForm = false; this.fetchItems();
                } catch { this.showToast('Terjadi kesalahan.', 'error'); }
            },
            async destroy(item) {
                if (!confirm(`Hapus beasiswa "${item.scholarship_name}"?`)) return;
                try {
                    const res = await fetch(`/scholarships/${item.id}`, {
                        method: 'DELETE',
                        headers: { Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    });
                    if (!res.ok) { this.showToast('Gagal menghapus data.', 'error'); return; }
                    this.showToast('Beasiswa berhasil dihapus.');
                    this.fetchItems();
                } catch { this.showToast('Terjadi kesalahan.', 'error'); }
            },
            showToast(message, type = 'success') {
                this.toast = { show: true, message, type };
                setTimeout(() => { this.toast.show = false; }, 2400);
            },
        };
    }
</script>
