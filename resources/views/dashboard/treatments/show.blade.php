<x-layouts.dashboard :title="'Pengobatan: '.$production->name" :heading="'Pengobatan: '.$production->name">
    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 16px; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px;">{{ $production->name }}</div>
            <div style="display: flex; flex-wrap: wrap; gap: 12px 24px; color: #555; font-size: 14px; margin-top: 8px;">
                <div>
                    <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px;">Resep</div>
                    <div style="font-weight: 600; color: #333;">{{ $production->concept?->name ?? '-' }}
                        @if ($production->concept)
                            <a href="{{ route('concepts.edit', $production->concept) }}" style="font-weight: 400; font-size: 12px; color: #2563eb; text-decoration: none; margin-left: 6px;">Edit</a>
                        @endif
                    </div>
                </div>
                <div>
                    <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px;">Kapasitas</div>
                    <div style="font-weight: 600; color: #333;">{{ formatWeight($production->target_weight_kg) }} kg</div>
                </div>
                <div>
                    <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px;">Pengobatan Hari Ke</div>
                    <div style="font-weight: 600; color: #333;">{{ $production->treatment_day ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px;">Waktu</div>
                    <div style="font-weight: 600; color: #333;"><span class="chip">{{ $production->treatment_time ?? '-' }}</span></div>
                </div>
                @if ($production->treatment_duration_days)
                <div>
                    <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px;">Lama Pengobatan</div>
                    <div style="font-weight: 600; color: #333;">{{ $production->treatment_duration_days }} hari</div>
                </div>
                @endif
                <div>
                    <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px;">Durasi</div>
                    <div style="font-weight: 600; color: #333;">{{ $production->is_forever ? 'Selamanya' : $production->duration_days.' hari' }}</div>
                </div>
                <div>
                    <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px;">Mulai Pakai Konsep</div>
                    <div style="font-weight: 600; color: #333;">{{ $production->start_date?->format('d-m-Y') ?? '-' }}</div>
                </div>
            </div>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 6px; flex-shrink: 0;">
            <a class="btn" href="{{ route('treatments.edit', $production) }}" style="font-size: 13px; padding: 6px 14px;">Edit</a>
            <a class="btn" href="{{ route('treatments.pdf', $production) }}" style="font-size: 13px; padding: 6px 14px;">PDF</a>
            <a class="btn" href="{{ route('treatments.index') }}" style="font-size: 13px; padding: 6px 14px;">Kembali</a>
            <form method="POST" action="{{ route('treatments.destroy', $production) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit" style="font-size: 13px; padding: 6px 14px;">Hapus</button>
            </form>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
        <div style="padding: 14px 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
            <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 2px;">Lokasi</div>
            <div style="font-weight: 600; color: #333;">{{ $production->location ?? '-' }}</div>
        </div>
        <div style="padding: 14px 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
            <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 2px;">Kandang</div>
            <div style="font-weight: 600; color: #333;">{{ $production->cage ?? '-' }}</div>
        </div>
        <div style="padding: 14px 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
            <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 2px;">Tanggal Campur</div>
            <div style="font-weight: 600; color: #333;">{{ $production->mix_date?->format('d-m-Y') ?? '-' }}</div>
        </div>
        <div style="padding: 14px 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
            <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 2px;">Tanggal Mulai Pakai Konsep</div>
            <div style="font-weight: 600; color: #333;">{{ $production->start_date?->format('d-m-Y') ?? '-' }}</div>
        </div>
    </div>

    <div style="padding: 14px 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); margin-bottom: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 2px;">Catatan</div>
                <div style="font-weight: 500; color: #555;">{{ $production->notes ?: 'Tidak ada catatan' }}</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2>Snapshot Item</h2>
            <span class="chip">Auto-scaling dari konsep</span>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Berat (kg)</th>
                        <th>Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($production->items as $row)
                        <tr>
                            <td>{{ $row->item?->name }}</td>
                            <td>{{ formatWeight($row->weight_kg) }}</td>
                            <td><span class="chip">{{ $row->source }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel" style="margin-bottom: 16px;">
        <div class="panel-header" style="padding: 10px 16px;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <label style="display: inline-flex; align-items: center; gap: 5px; cursor: pointer; font-size: 13px;">
                    <input type="radio" name="input-mode" value="golongan" checked data-mode-toggle>
                    <span style="font-weight: 600;">Golongan</span>
                </label>
                <label style="display: inline-flex; align-items: center; gap: 5px; cursor: pointer; font-size: 13px;">
                    <input type="radio" name="input-mode" value="tab" data-mode-toggle>
                    <span style="font-weight: 600;">Tab (Split Batch)</span>
                </label>
            </div>
            <span class="chip" style="font-size: 12px;">Sisa: {{ formatWeight($tabAvailableKg) }} kg</span>
        </div>
    </div>

    <div id="mode-golongan" class="panel">
        <div class="panel-header">
            <h2>Golongan</h2>
            <form method="POST" action="{{ route('treatments.groups.store', $production) }}" style="display: flex; gap: 8px; align-items: end;">
                @csrf
                <div class="field" style="margin: 0;">
                    <input type="text" name="name" placeholder="Nama Golongan" style="width: 200px;">
                </div>
                <button class="btn btn-primary" type="submit">Tambah</button>
            </form>
        </div>
        <div class="panel-body">
            <div class="stack" style="gap: 10px;">
                @forelse ($production->groups as $group)
                    <div style="background: #f9fafb; border-radius: 6px; padding: 12px 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <div style="font-weight: 700; font-size: 15px; color: #1a1a2e;">{{ $group->name }}</div>
                            <form method="POST" action="{{ route('treatments.groups.destroy', $group) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit" style="font-size: 12px; padding: 4px 10px;">Hapus</button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('treatments.groups.items.store', $group) }}" class="group-item-form" style="display: flex; gap: 8px; align-items: end; margin-bottom: 8px;">
                            @csrf
                            <div class="field" style="margin: 0; flex: 1;">
                                <select name="item_id" class="item-select" style="width: 100%;">
                                    <option value="">Pilih Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field" style="margin: 0; width: 100px;">
                                <input type="number" step="0.0001" name="weight_value" placeholder="Berat" class="weight-input" style="width: 100%;">
                            </div>
                            <div class="field" style="margin: 0; width: 90px;">
                                <select name="weight_unit_id" style="width: 100%;">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label style="display: flex; align-items: center; gap: 4px; cursor: pointer; font-size: 13px; white-space: nowrap;">
                                <input type="checkbox" name="is_dosis" value="1" class="dosis-toggle" data-group-id="{{ $group->id }}">
                                Dosis
                            </label>
                            <button class="btn btn-primary" type="submit" style="font-size: 12px; padding: 6px 12px;">Tambah</button>
                        </form>

                        @if ($group->items->isNotEmpty())
                            <table class="table" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Berat</th>
                                        <th>Dosis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group->items as $gi)
                                        @php
                                            $displayWeight = $gi->weight_input_value && $gi->inputUnit
                                                ? formatWeight($gi->weight_input_value).' '.$gi->inputUnit->name
                                                : formatWeight($gi->weight_kg).' kg';
                                        @endphp
                                        <tr>
                                            <td>{{ $gi->item?->name }}</td>
                                            <td class="weight-cell">
                                                <span class="weight-display">{{ $displayWeight }}</span>
                                                <form method="POST" action="{{ route('treatments.groups.items.update', $gi) }}" class="edit-weight-form" style="display: none; gap: 4px; align-items: center;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" step="0.0001" name="weight_value" value="{{ $gi->weight_input_value ?? formatWeight($gi->weight_kg) }}" style="width: 80px;">
                                                    <select name="weight_unit_id" style="width: 70px;">
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}" @selected($gi->weight_input_unit_id == $unit->id)>{{ $unit->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn" type="submit" style="font-size: 11px; padding: 2px 8px;">Simpan</button>
                                                    <button type="button" class="btn cancel-edit" style="font-size: 11px; padding: 2px 8px;">Batal</button>
                                                </form>
                                            </td>
                                            <td>{!! $gi->is_dosis ? '<span class="chip">Dosis</span>' : '<span class="chip" style="background: #eee; color: #999;">Non</span>' !!}</td>
                                            <td>
                                                <button type="button" class="btn btn-edit-weight" style="font-size: 12px; padding: 3px 8px;">Edit</button>
                                                <form method="POST" action="{{ route('treatments.groups.items.destroy', $gi) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit" style="font-size: 12px; padding: 4px 10px;">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @empty
                    <div class="muted">Belum ada golongan.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="mode-tab" class="panel" style="display: none;">
        <div class="panel-header">
            <h2>Tab (Split Batch)</h2>
            <form method="POST" action="{{ route('treatments.tabs.store', $production) }}" style="display: flex; gap: 8px; align-items: end;">
                @csrf
                <div class="field" style="margin: 0;">
                    <input type="text" name="name" placeholder="Nama Tab" style="width: 160px;">
                </div>
                <div class="field" style="margin: 0; width: 120px;">
                    <input type="number" step="0.0001" name="input_weight_value" placeholder="Ambil (kg)" style="width: 100%;">
                </div>
                <div class="field" style="margin: 0; width: 100px;">
                    <select name="input_weight_unit_id" style="width: 100%;">
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Buat Tab</button>
            </form>
        </div>
        <div class="panel-body">
            @if ($production->tabs->isNotEmpty())
                @php $cumulativeUsed = 0; @endphp
                <div style="display: flex; flex-wrap: wrap; gap: 8px; padding: 8px 12px; background: #f0f7ff; border-radius: 6px; margin-bottom: 12px; font-size: 13px;">
                    <strong>Split Batch:</strong>
                    @foreach ($production->tabs as $tab)
                        @php $cumulativeUsed += (float) $tab->input_weight_kg; @endphp
                        <span class="chip">{{ $tab->name }}: {{ formatWeight($tab->input_weight_kg) }} kg</span>
                        <span class="chip">Sisa: {{ formatWeight($tab->remaining_weight_kg) }} kg</span>
                    @endforeach
                    <span class="chip">Total: {{ formatWeight($cumulativeUsed) }} kg</span>
                    <span class="chip">Sisa Global: {{ formatWeight($tabAvailableKg) }} kg</span>
                </div>
            @endif

            <div class="stack" style="gap: 10px;">
                @forelse ($production->tabs as $tab)
                    <div style="background: #f9fafb; border-radius: 6px; padding: 12px 14px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <span style="font-weight: 700; font-size: 15px; color: #1a1a2e;">{{ $tab->name }}</span>
                                <span class="chip">Ambil: {{ formatWeight($tab->input_weight_kg) }} kg</span>
                                <span class="chip">Sisa: {{ formatWeight($tab->remaining_weight_kg) }} kg</span>
                            </div>
                            <form method="POST" action="{{ route('treatments.tabs.destroy', $tab) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit" style="font-size: 12px; padding: 4px 10px;">Hapus</button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('treatments.tabs.items.store', $tab) }}" class="tab-item-form" style="display: flex; gap: 8px; align-items: end;">
                            @csrf
                            <div class="field" style="margin: 0; flex: 1;">
                                <select name="item_id" class="item-select" style="width: 100%;">
                                    <option value="">Pilih Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field" style="margin: 0; width: 100px;">
                                <input type="number" step="0.0001" name="weight_value" placeholder="Berat" class="weight-input" style="width: 100%;">
                            </div>
                            <div class="field" style="margin: 0; width: 90px;">
                                <select name="weight_unit_id" style="width: 100%;">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label style="display: flex; align-items: center; gap: 4px; cursor: pointer; font-size: 13px; white-space: nowrap;">
                                <input type="checkbox" name="is_dosis" value="1" class="dosis-toggle" data-tab-id="{{ $tab->id }}" data-remaining-kg="{{ $tab->remaining_weight_kg }}">
                                Dosis
                            </label>
                            <button class="btn btn-primary" type="submit" style="font-size: 12px; padding: 6px 12px;">Tambah</button>
                        </form>

                        @if ($tab->items->isNotEmpty())
                            <table class="table" style="font-size: 13px; margin-top: 8px;">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Berat</th>
                                        <th>Dosis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tab->items as $ti)
                                        @php
                                            $displayWeight = $ti->weight_input_value && $ti->inputUnit
                                                ? formatWeight($ti->weight_input_value).' '.$ti->inputUnit->name
                                                : formatWeight($ti->weight_kg).' kg';
                                        @endphp
                                        <tr>
                                            <td>{{ $ti->item?->name }}</td>
                                            <td class="weight-cell">
                                                <span class="weight-display">{{ $displayWeight }}</span>
                                                <form method="POST" action="{{ route('treatments.tabs.items.update', $ti) }}" class="edit-weight-form" style="display: none; gap: 4px; align-items: center;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" step="0.0001" name="weight_value" value="{{ $ti->weight_input_value ?? formatWeight($ti->weight_kg) }}" style="width: 80px;">
                                                    <select name="weight_unit_id" style="width: 70px;">
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}" @selected($ti->weight_input_unit_id == $unit->id)>{{ $unit->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn" type="submit" style="font-size: 11px; padding: 2px 8px;">Simpan</button>
                                                    <button type="button" class="btn cancel-edit" style="font-size: 11px; padding: 2px 8px;">Batal</button>
                                                </form>
                                            </td>
                                            <td>{!! $ti->is_dosis ? '<span class="chip">Dosis</span>' : '<span class="chip" style="background: #eee; color: #999;">Non</span>' !!}</td>
                                            <td>
                                                <button type="button" class="btn btn-edit-weight" style="font-size: 12px; padding: 3px 8px;">Edit</button>
                                                <form method="POST" action="{{ route('treatments.tabs.items.destroy', $ti) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit" style="font-size: 12px; padding: 4px 10px;">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @empty
                    <div class="muted">Belum ada TAB.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="dosis-modal" style="display: none;">
        <div class="modal" style="max-width: 480px;">
            <div class="modal-header">
                <h3>Kalkulator Dosis</h3>
                <button class="btn" id="dosis-close" style="font-size: 18px; padding: 2px 12px;">&times;</button>
            </div>
            <div class="modal-body">
                <div class="grid-2">
                    <div class="field">
                        <div class="label">Item</div>
                        <input type="text" id="dosis-item-name" readonly>
                    </div>
                    <div class="field">
                        <div class="label">Target</div>
                        <input type="text" id="dosis-target" readonly value="{{ formatWeight($production->target_weight_kg) }} kg">
                    </div>
                </div>
                <div style="border-top: 1px solid #e0e4e8; margin: 12px 0;"></div>
                <div class="grid-2">
                    <div class="field">
                        <div class="label">Berat Dosis</div>
                        <input type="number" step="0.0001" id="dosis-weight" placeholder="1">
                    </div>
                    <div class="field">
                        <div class="label">Satuan</div>
                        <select id="dosis-unit">
                            @foreach ($units as $unit)
                                <option value="{{ $unit->conversion_to_kg }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <div class="label">Per</div>
                        <input type="number" step="0.0001" id="dosis-per" placeholder="1" value="1">
                    </div>
                    <div class="field">
                        <div class="label">Satuan Per</div>
                        <select id="dosis-per-unit">
                            @foreach ($units as $unit)
                                <option value="{{ $unit->conversion_to_kg }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="border-top: 1px solid #e0e4e8; margin: 12px 0;"></div>
                <div style="background: #e8f5e9; border-radius: 6px; text-align: center; padding: 12px;">
                    <div style="font-size: 12px; color: #666;">Hasil Perhitungan:</div>
                    <strong style="font-size: 24px;" id="dosis-result">0 kg</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="dosis-close-btn">Batal</button>
                <button class="btn btn-primary" id="dosis-pakai">Pakai Hasil</button>
            </div>
        </div>
    </div>

    <style>
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1000; display: flex; align-items: center; justify-content: center; }
        .modal { background: #fff; border-radius: 8px; width: 100%; box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
        .modal-header, .modal-footer { padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; }
        .modal-body { padding: 0 20px 16px; }
        .modal-footer { border-top: 1px solid #eee; gap: 8px; justify-content: flex-end; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        function switchMode(mode) {
            const panelGol = document.getElementById('mode-golongan');
            const panelTab = document.getElementById('mode-tab');
            if (mode === 'golongan') {
                panelGol.style.display = '';
                panelTab.style.display = 'none';
            } else {
                panelGol.style.display = 'none';
                panelTab.style.display = '';
            }
        }

        let currentMode = document.querySelector('[data-mode-toggle]:checked') ? document.querySelector('[data-mode-toggle]:checked').value : 'golongan';

        if (window.location.hash === '#tab') {
            currentMode = 'tab';
            document.querySelectorAll('[data-mode-toggle]').forEach(function (r) { r.checked = (r.value === 'tab'); });
            switchMode('tab');
        }

        function panelHasUnsavedInputs(mode) {
            const panel = (mode === 'golongan') ? document.getElementById('mode-golongan') : document.getElementById('mode-tab');
            if (!panel) return false;
            const inputs = panel.querySelectorAll('input[type="number"], input[type="text"], textarea, select');
            for (let i = 0; i < inputs.length; i++) {
                const el = inputs[i];
                if (el.type === 'number' || el.type === 'text' || el.tagName.toLowerCase() === 'textarea') {
                    if (el.value && el.value.toString().trim() !== '') return true;
                } else if (el.tagName.toLowerCase() === 'select') {
                    if (el.selectedIndex > 0) return true;
                }
            }
            return false;
        }

        document.querySelectorAll('[data-mode-toggle]').forEach(function (radio) {
            radio.addEventListener('click', function (e) {
                const newMode = this.value;
                if (newMode === currentMode) return;

                if (panelHasUnsavedInputs(currentMode)) {
                    const ok = confirm('Anda sudah mengisi beberapa data. Mengganti mode akan menghapus input yang belum disimpan. Lanjutkan?');
                    if (!ok) {
                        e.preventDefault();
                        document.querySelectorAll('[data-mode-toggle]').forEach(function (r) { r.checked = (r.value === currentMode); });
                        return;
                    } else {
                        const panel = (currentMode === 'golongan') ? document.getElementById('mode-golongan') : document.getElementById('mode-tab');
                        if (panel) {
                            const inputsToClear = panel.querySelectorAll('input[type="number"], input[type="text"], textarea');
                            inputsToClear.forEach(function (inp) { inp.value = ''; });
                            const selects = panel.querySelectorAll('select');
                            selects.forEach(function (s) { s.selectedIndex = 0; });
                            const checkboxes = panel.querySelectorAll('input[type="checkbox"]');
                            checkboxes.forEach(function (c) { c.checked = false; });
                        }
                    }
                }

                switchMode(newMode);
                currentMode = newMode;
            });
            if (radio.checked) {
                switchMode(radio.value);
            }
        });

        const productionTargetKg = {{ $production->target_weight_kg }};
        const dosisModal = document.getElementById('dosis-modal');
        const dosisItemName = document.getElementById('dosis-item-name');
        const dosisWeight = document.getElementById('dosis-weight');
        const dosisUnit = document.getElementById('dosis-unit');
        const dosisPer = document.getElementById('dosis-per');
        const dosisPerUnit = document.getElementById('dosis-per-unit');
        const dosisResult = document.getElementById('dosis-result');
        const dosisTarget = document.getElementById('dosis-target');
        let activeForm = null;
        let activeItemSelect = null;
        let isTabContext = false;
        let contextId = null;
        let pendingDosisToggle = null;
        let dosisTargetKg = productionTargetKg;

        function openDosisModal(form, toggleCb) {
            activeForm = form;
            pendingDosisToggle = toggleCb;
            const itemSelect = form.querySelector('.item-select');
            const weightInput = form.querySelector('.weight-input');
            activeItemSelect = itemSelect;

            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
            dosisItemName.value = selectedOption ? selectedOption.text : '';

            const groupId = toggleCb ? toggleCb.dataset.groupId : null;
            const tabId = toggleCb ? toggleCb.dataset.tabId : null;
            isTabContext = !!tabId;
            contextId = groupId || tabId;

            if (isTabContext) {
                const remaining = parseFloat(toggleCb.dataset.remainingKg) || 0;
                dosisTargetKg = remaining;
                dosisTarget.value = remaining.toFixed(2) + ' kg (sisa tab)';
            } else {
                dosisTargetKg = productionTargetKg;
                dosisTarget.value = {{ formatWeight($production->target_weight_kg) }} + ' kg';
            }

            // Disable manual weight inputs when dosis mode is active
            if (weightInput) {
                weightInput.disabled = true;
                weightInput.placeholder = 'Dosis (auto)';
            }
            const unitSelect = form.querySelector('select[name="weight_unit_id"]');
            if (unitSelect) unitSelect.disabled = true;

            dosisModal.style.display = 'flex';
            recalcDosis();
        }

        function recalcDosis() {
            const w = parseFloat(dosisWeight.value) || 0;
            const c = parseFloat(dosisUnit.value) || 1;
            const p = parseFloat(dosisPer.value) || 1;
            const pc = parseFloat(dosisPerUnit.value) || 1;
            if (w <= 0) { dosisResult.textContent = '0 kg'; return; }
            const perKg = (w * c) / (p * pc);
            const result = perKg * dosisTargetKg;
            dosisResult.textContent = result.toFixed(4) + ' kg';
        }

        dosisWeight.addEventListener('input', recalcDosis);
        dosisUnit.addEventListener('change', recalcDosis);
        dosisPer.addEventListener('input', recalcDosis);
        dosisPerUnit.addEventListener('change', recalcDosis);

        document.getElementById('dosis-pakai').addEventListener('click', function () {
            const w = parseFloat(dosisWeight.value) || 0;
            const c = parseFloat(dosisUnit.value) || 1;
            const p = parseFloat(dosisPer.value) || 1;
            const pc = parseFloat(dosisPerUnit.value) || 1;
            if (w <= 0) return;
            const perKg = (w * c) / (p * pc);
            const result = perKg * dosisTargetKg;

            const weightInput = activeForm.querySelector('.weight-input');
            const unitSelect = activeForm.querySelector('select[name="weight_unit_id"]');

            // Enable fields so they get submitted with the form
            if (weightInput) {
                weightInput.value = result.toFixed(4);
                weightInput.disabled = false;
            }
            // Set unit to kg (conversion factor = 1)
            if (unitSelect) {
                const kgOption = Array.from(unitSelect.options).find(o => parseFloat(o.value) === 1);
                if (kgOption) kgOption.selected = true;
                unitSelect.disabled = false;
            }

            dosisModal.style.display = 'none';
            // Reset dosis toggle reference
            activeForm = null;
            pendingDosisToggle = null;
            activeForm.submit();
        });

        function closeDosisModal() {
            dosisModal.style.display = 'none';
            if (activeForm) {
                const weightInput = activeForm.querySelector('.weight-input');
                if (weightInput) {
                    weightInput.disabled = false;
                    weightInput.placeholder = 'Berat';
                }
                const unitSelect = activeForm.querySelector('select[name="weight_unit_id"]');
                if (unitSelect) unitSelect.disabled = false;
                if (pendingDosisToggle) {
                    pendingDosisToggle.checked = false;
                }
            }
            activeForm = null;
            pendingDosisToggle = null;
        }

        document.getElementById('dosis-close').addEventListener('click', closeDosisModal);
        document.getElementById('dosis-close-btn').addEventListener('click', closeDosisModal);

        // Dosis toggle: open modal on check
        document.querySelectorAll('.dosis-toggle').forEach(function (cb) {
            cb.addEventListener('change', function () {
                if (this.checked) {
                    const form = this.closest('form');
                    const itemSelect = form.querySelector('.item-select');
                    if (!itemSelect || !itemSelect.value) {
                        alert('Pilih item terlebih dahulu.');
                        this.checked = false;
                        return;
                    }
                    openDosisModal(form, this);
                } else {
                    const form = this.closest('form');
                    const weightInput = form.querySelector('.weight-input');
                    if (weightInput) {
                        weightInput.disabled = false;
                        weightInput.placeholder = 'Berat';
                    }
                    const unitSelect = form.querySelector('select[name="weight_unit_id"]');
                    if (unitSelect) unitSelect.disabled = false;
                }
            });
        });

        // Edit weight button logic
        document.querySelectorAll('.btn-edit-weight').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const row = this.closest('tr');
                const display = row.querySelector('.weight-display');
                const form = row.querySelector('.edit-weight-form');
                if (display) display.style.display = 'none';
                if (form) form.style.display = 'inline-flex';
                this.style.display = 'none';
            });
        });

        document.querySelectorAll('.cancel-edit').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const row = this.closest('tr');
                const display = row.querySelector('.weight-display');
                const form = row.querySelector('.edit-weight-form');
                const editBtn = row.querySelector('.btn-edit-weight');
                if (display) display.style.display = '';
                if (form) form.style.display = 'none';
                if (editBtn) editBtn.style.display = '';
            });
        });
    });
    </script>
</x-layouts.dashboard>
