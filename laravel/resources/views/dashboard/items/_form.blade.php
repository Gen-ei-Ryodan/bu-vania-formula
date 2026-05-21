<div class="grid-2">
    <div class="field">
        <div class="label">Nama</div>
        <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}">
    </div>
    <div class="field">
        <div class="label">Kategori</div>
        <select name="category">
            @php($val = old('category', $item->category ?? 'bahan_pokok'))
            <option value="bahan_pokok" @selected($val === 'bahan_pokok')>bahan_pokok</option>
            <option value="vitamin" @selected($val === 'vitamin')>vitamin</option>
            <option value="obat" @selected($val === 'obat')>obat</option>
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
