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
</div>
