<div class="form-grid">
    <div class="field">
        <div class="label">Nama</div>
        <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" placeholder="Nama item...">
    </div>
    <div class="field">
        <div class="label">Kategori</div>
        <select name="category_id">
            @php($val = (int) old('category_id', $item->category_id ?? 0))
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected($val === $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="field">
        <div class="label">Unit Default</div>
        <select name="default_unit_id">
            @php($u = (int) old('default_unit_id', $item->default_unit_id ?? 0))
            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" @selected((isset($u) && $u === $unit->id))>{{ $unit->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="field">
        <div class="label">Harga</div>
        <input type="number" name="price" value="{{ old('price', $item->price ?? '') }}" min="0.01" step="0.01" placeholder="10000" required>
        <div class="field-hint">Harga beli dalam rupiah.</div>
    </div>
    <div class="field">
        <div class="label">Harga per</div>
        <div class="input-group">
            <input type="number" name="price_unit_value" value="{{ old('price_unit_value', $item->price_unit_value ?? 1) }}" min="0.000001" step="0.000001" required>
            <select name="price_unit_id" required>
                @php($priceUnit = (int) old('price_unit_id', $item->price_unit_id ?? 0))
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}" @selected($priceUnit === $unit->id)>{{ $unit->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field-hint">Contoh: Rp10.000 per 1 kg atau Rp500 per 100 gram.</div>
    </div>
</div>
