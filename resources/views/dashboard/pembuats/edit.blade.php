<x-layouts.dashboard title="Edit Pembuat" heading="Edit Pembuat">
    <div class="page-hero">
        <h1>Edit Pembuat</h1>
        <p>{{ $pembuat->name }}</p>
    </div>
    <div class="content-section">
        <form method="POST" action="{{ route('pembuats.update', $pembuat) }}">
            @csrf
            @method('PUT')
            <div class="form-card">
                <div class="form-card-header"><h3>Informasi Pembuat</h3></div>
                <div class="form-card-body">
                    <div class="field" style="max-width: 400px;">
                        <div class="label">Nama Pembuat</div>
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
