<x-layouts.dashboard title="Kategori Item" heading="Kategori">
    <div class="page-hero">
        <h1>Master Kategori Item</h1>
        <p>Kelola kategori untuk item</p>
        <div class="page-hero-actions">
            <button class="btn btn-primary" type="button" data-modal-toggle="create-modal">+ Tambah Kategori</button>
        </div>
    </div>

    <div class="content-section">
        <div class="card">
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap" style="border: none; border-radius: 0;">
                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Nama</th>
                                <th style="width: 180px;" class="cell-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $cat)
                                <tr>
                                    <td class="cell-muted">#{{ $cat->id }}</td>
                                    <td><strong>{{ $cat->name }}</strong></td>
                                    <td class="cell-actions">
                                        <button class="btn btn-sm" type="button" data-modal-toggle="edit-modal-{{ $cat->id }}">Edit</button>
                                        <form method="POST" action="{{ route('categories.destroy', $cat) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus kategori?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($categories->isEmpty())
                                <tr><td colspan="3" style="text-align: center; padding: 48px; color: var(--text-muted);">Belum ada data kategori.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal-overlay" id="create-modal" style="display: none;">
        <div class="modal">
            <div class="modal-header">
                <h3>Tambah Kategori</h3>
                <button class="btn btn-ghost btn-sm" type="button" data-modal-close style="width: 34px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="field">
                        <div class="label">Nama Kategori</div>
                        <input type="text" name="name" placeholder="Nama kategori..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-ghost" type="button" data-modal-close>Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modals --}}
    @foreach ($categories as $cat)
        <div class="modal-overlay" id="edit-modal-{{ $cat->id }}" style="display: none;">
            <div class="modal">
                <div class="modal-header">
                    <h3>Edit Kategori</h3>
                    <button class="btn btn-ghost btn-sm" type="button" data-modal-close style="width: 34px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('categories.update', $cat) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="field">
                            <div class="label">Nama Kategori</div>
                            <input type="text" name="name" value="{{ $cat->name }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-ghost" type="button" data-modal-close>Batal</button>
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-modal-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = this.getAttribute('data-modal-toggle');
                document.getElementById(id).style.display = 'flex';
            });
        });
        document.querySelectorAll('[data-modal-close]').forEach(function (el) {
            el.addEventListener('click', function () {
                var modal = this.closest('.modal-overlay');
                if (modal) modal.style.display = 'none';
            });
        });
        document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
            overlay.addEventListener('click', function (e) {
                if (e.target === this) this.style.display = 'none';
            });
        });
    });
    </script>
    @endpush
</x-layouts.dashboard>
