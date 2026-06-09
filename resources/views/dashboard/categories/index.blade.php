<x-layouts.dashboard title="Kategori Item" heading="Master Kategori Item">
    <div class="panel">
        <div class="panel-header">
            <h2>Kategori</h2>
            <button class="btn btn-primary" type="button" data-modal-toggle="create-modal">Tambah</button>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td>{{ $cat->name }}</td>
                            <td>
                                <button class="btn" type="button" data-modal-toggle="edit-modal-{{ $cat->id }}">Edit</button>
                                <form method="POST" action="{{ route('categories.destroy', $cat) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Hapus kategori?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if ($categories->isEmpty())
                        <tr>
                            <td colspan="3" class="muted">Belum ada data.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal-dialog" id="create-modal">
        <div class="modal-dialog-overlay" data-modal-close></div>
        <div class="modal-dialog-content">
            <div class="modal-dialog-header">
                <h2>Tambah Kategori</h2>
                <button class="btn" type="button" data-modal-close>Tutup</button>
            </div>
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                <div class="modal-dialog-body">
                    <div class="field">
                        <div class="label">Nama Kategori</div>
                        <input type="text" name="name" placeholder="Nama kategori..." required>
                    </div>
                </div>
                <div class="modal-dialog-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modals --}}
    @foreach ($categories as $cat)
        <div class="modal-dialog" id="edit-modal-{{ $cat->id }}">
            <div class="modal-dialog-overlay" data-modal-close></div>
            <div class="modal-dialog-content">
                <div class="modal-dialog-header">
                    <h2>Edit Kategori</h2>
                    <button class="btn" type="button" data-modal-close>Tutup</button>
                </div>
                <form method="POST" action="{{ route('categories.update', $cat) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-dialog-body">
                        <div class="field">
                            <div class="label">Nama Kategori</div>
                            <input type="text" name="name" value="{{ $cat->name }}" required>
                        </div>
                    </div>
                    <div class="modal-dialog-footer">
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
                this.closest('.modal-dialog').style.display = 'none';
            });
        });
    });
    </script>
    @endpush
</x-layouts.dashboard>
