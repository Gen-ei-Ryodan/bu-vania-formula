<x-layouts.dashboard title="Edit Konsep Dari" heading="Edit Konsep Dari">
    <div class="page-hero">
        <h1>Edit Konsep Dari</h1>
        <p>{{ $pembuat->name }}</p>
    </div>
    <div class="content-section">
        <form method="POST" action="{{ route('pembuats.update', $pembuat) }}">
            @csrf
            @method('PUT')
            <div class="form-card">
                <div class="form-card-header"><h3>Informasi Konsep Dari</h3></div>
                <div class="form-card-body">
                    <div class="field" style="max-width: 400px;">
                        <div class="label">Nama</div>
                        <input type="text" name="name" value="{{ old('name', $pembuat->name) }}" placeholder="Nama pembuat..." required>
                    </div>
                </div>
                <div class="form-card-footer">
                    <a class="btn btn-ghost" href="{{ route('pembuats.index') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.dashboard>
