<div x-show="toast.show" x-transition class="fixed bottom-4 right-4 z-50 rounded-xl px-4 py-3 text-sm font-semibold shadow-lg" :class="toast.type === 'error' ? 'bg-rose-600 text-white' : 'bg-emerald-600 text-white'" style="display: none;" x-text="toast.message"></div>

<script>
    function announcementsCrud() {
        return {
            loading: false, search: '', modalForm: false, modalShow: false,
            editMode: false, selectedItem: {}, items: [],
            form: { scholarship_id: '', title: '', date: '', publish_status: false },
            errors: {}, toast: { show: false, message: '', type: 'success' },
            get filteredItems() {
                const keyword = this.search.toLowerCase().trim();
                if (!keyword) return this.items;
                return this.items.filter((item) =>
                    [item.title].filter(Boolean).some((v) => String(v).toLowerCase().includes(keyword))
                );
            },
            init() { this.fetchItems(); },
            async fetchItems() {
                this.loading = true;
                try {
                    const res = await fetch('/announcements', { headers: { Accept: 'application/json' } });
                    const result = await res.json();
                    this.items = Array.isArray(result.data) ? result.data : [];
                } catch { this.showToast('Gagal memuat data pengumuman.', 'error'); }
                finally { this.loading = false; }
            },
            openCreateModal() {
                this.editMode = false; this.selectedItem = {};
                this.form = { scholarship_id: '', title: '', date: '', publish_status: false };
                this.errors = {}; this.modalForm = true;
            },
            openEditModal(item) {
                this.editMode = true; this.selectedItem = item;
                this.form = { scholarship_id: item.scholarship_id ?? '', title: item.title ?? '', date: item.date ?? '', publish_status: !!item.publish_status };
                this.errors = {}; this.modalForm = true;
            },
            openShowModal(item) { this.selectedItem = item; this.modalShow = true; },
            closeFormModal() { this.modalForm = false; },
            async submitForm() {
                this.errors = {};
                const isUpdate = this.editMode && this.selectedItem.id;
                const url = isUpdate ? `/announcements/${this.selectedItem.id}` : '/announcements';
                const method = isUpdate ? 'PUT' : 'POST';
                try {
                    const res = await fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify(this.form),
                    });
                    const result = await res.json();
                    if (!res.ok) { this.errors = result.errors ?? {}; this.showToast(result.message ?? 'Validasi gagal.', 'error'); return; }
                    this.showToast(isUpdate ? 'Data berhasil diubah.' : 'Data berhasil ditambahkan.');
                    this.modalForm = false; this.fetchItems();
                } catch { this.showToast('Terjadi kesalahan.', 'error'); }
            },
            async destroy(item) {
                if (!confirm(`Hapus pengumuman "${item.title}"?`)) return;
                try {
                    const res = await fetch(`/announcements/${item.id}`, {
                        method: 'DELETE',
                        headers: { Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    });
                    if (!res.ok) { this.showToast('Gagal menghapus data.', 'error'); return; }
                    this.showToast('Data berhasil dihapus.'); this.fetchItems();
                } catch { this.showToast('Terjadi kesalahan.', 'error'); }
            },
            showToast(message, type = 'success') {
                this.toast = { show: true, message, type };
                setTimeout(() => { this.toast.show = false; }, 2400);
            },
        };
    }
</script>
