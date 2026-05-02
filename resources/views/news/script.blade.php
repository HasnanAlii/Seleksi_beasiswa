<div
    x-show="toast.show"
    x-transition
    class="fixed bottom-4 right-4 z-50 rounded-xl px-4 py-3 text-sm font-semibold shadow-lg"
    :class="toast.type === 'error' ? 'bg-rose-600 text-white' : 'bg-emerald-600 text-white'"
    style="display: none;"
    x-text="toast.message"
></div>

<script>
    function newsCrud() {
        return {
            loading: false,
            search: '',
            modalForm: false,
            modalShow: false,
            editMode: false,
            selectedItem: {},
            items: [],
            form: {
                title: '',
                content: '',
            },
            errors: {},
            toast: {
                show: false,
                message: '',
                type: 'success',
            },
            get filteredItems() {
                const keyword = this.search.toLowerCase().trim();

                if (!keyword) {
                    return this.items;
                }

                return this.items.filter((item) => {
                    return [item.title, item.content]
                        .filter(Boolean)
                        .some((value) => String(value).toLowerCase().includes(keyword));
                });
            },
            init() {
                this.fetchItems();
            },
            async fetchItems() {
                this.loading = true;
                this.errors = {};

                try {
                    const response = await fetch('/news', {
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    const result = await response.json();
                    this.items = Array.isArray(result.data) ? result.data : [];
                } catch (error) {
                    this.showToast('Gagal memuat data berita.', 'error');
                } finally {
                    this.loading = false;
                }
            },
            openCreateModal() {
                this.editMode = false;
                this.selectedItem = {};
                this.form = {
                    title: '',
                    content: '',
                };
                this.errors = {};
                this.modalForm = true;
            },
            openEditModal(item) {
                this.editMode = true;
                this.selectedItem = item;
                this.form = {
                    title: item.title ?? '',
                    content: item.content ?? '',
                };
                this.errors = {};
                this.modalForm = true;
            },
            openShowModal(item) {
                this.selectedItem = item;
                this.modalShow = true;
            },
            closeFormModal() {
                this.modalForm = false;
            },
            async submitForm() {
                this.errors = {};

                const isUpdate = this.editMode && this.selectedItem.id;
                const url = isUpdate ? `/news/${this.selectedItem.id}` : '/news';
                const method = isUpdate ? 'PUT' : 'POST';

                try {
                    const response = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(this.form),
                    });

                    const result = await response.json();

                    if (response.status === 422) {
                        this.errors = Object.fromEntries(
                            Object.entries(result.errors || {}).map(([key, value]) => [key, value[0]])
                        );
                        return;
                    }

                    if (!response.ok) {
                        this.showToast('Proses data berita gagal.', 'error');
                        return;
                    }

                    this.modalForm = false;
                    await this.fetchItems();
                    this.showToast(isUpdate ? 'Berita berhasil diubah.' : 'Berita berhasil ditambahkan.');
                } catch (error) {
                    this.showToast('Terjadi kesalahan jaringan.', 'error');
                }
            },
            async destroy(item) {
                const confirmed = window.confirm('Yakin ingin menghapus berita ini?');

                if (!confirmed) {
                    return;
                }

                try {
                    const response = await fetch(`/news/${item.id}`, {
                        method: 'DELETE',
                        headers: {
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    });

                    if (!response.ok) {
                        this.showToast('Gagal menghapus berita.', 'error');
                        return;
                    }

                    await this.fetchItems();
                    this.showToast('Berita berhasil dihapus.');
                } catch (error) {
                    this.showToast('Terjadi kesalahan jaringan.', 'error');
                }
            },
            showToast(message, type = 'success') {
                this.toast = {
                    show: true,
                    message,
                    type,
                };

                setTimeout(() => {
                    this.toast.show = false;
                }, 2400);
            },
        };
    }
</script>
