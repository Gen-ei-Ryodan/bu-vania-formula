<x-layouts.dashboard :title="'Production: '.$production->name" :heading="'Production: '.$production->name">
    <div class="grid-4">
        <div class="card">
            <div class="muted">Concept</div>
            <strong style="font-size: 18px;">{{ $production->concept?->name }}</strong>
        </div>
        <div class="card">
            <div class="muted">Jenis</div>
            <strong style="font-size: 18px;">{{ ucfirst($production->production_type) }}</strong>
            @if ($production->production_type === 'pengobatan')
                <div>Hari ke-{{ $production->treatment_day }} ({{ $production->treatment_time }})</div>
            @endif
        </div>
        <div class="card">
            <div class="muted">Target (kg)</div>
            <strong style="font-size: 18px;">{{ number_format($production->target_weight_kg, 2) }}</strong>
        </div>
        <div class="card">
            <div class="muted">Durasi</div>
            <strong style="font-size: 18px;">{{ $production->is_forever ? 'Selamanya' : $production->duration_days.' hari' }}</strong>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <h2>Snapshot Production Items</h2>
            <div class="actions">
                <a class="btn" href="{{ route('productions.pdf', $production) }}">PDF</a>
                @if ($production->items->isEmpty())
                    <form method="POST" action="{{ route('productions.generate', $production) }}">
                        @csrf
                        <button class="btn btn-primary" type="submit">Generate</button>
                    </form>
                @else
                    <span class="chip">Generated</span>
                @endif
                <a class="btn" href="{{ route('productions.index') }}">Kembali</a>
                <form method="POST" action="{{ route('productions.destroy', $production) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Hapus</button>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Weight (kg)</th>
                        <th>Source</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($production->items as $row)
                        <tr>
                            <td>{{ $row->item?->name }}</td>
                            <td>{{ number_format($row->weight_kg, 2) }}</td>
                            <td><span class="chip">{{ $row->source }}</span></td>
                        </tr>
                    @endforeach
                    @if ($production->items->isEmpty())
                        <tr>
                            <td colspan="3" class="muted">Belum ada snapshot. Klik Generate.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid-2">
        <div class="panel">
            <div class="panel-header">
                <h2>Golongan (Global Add-on)</h2>
                <span class="chip">Sisa untuk TAB: {{ number_format($tabAvailableKg, 2) }} kg</span>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('productions.groups.store', $production) }}">
                    @csrf
                    <div class="inline">
                        <div class="field w-280">
                            <div class="label">Nama Golongan</div>
                            <input type="text" name="name" placeholder="Golongan 1">
                        </div>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>

                <div class="divider"></div>

                <div class="stack">
                    @foreach ($production->groups as $group)
                        <div class="panel">
                            <div class="panel-header">
                                <h2>{{ $group->name }}</h2>
                                <span class="chip">ID: {{ $group->id }}</span>
                                <form method="POST" action="{{ route('groups.destroy', $group) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                            <div class="panel-body">
                                <form method="POST" action="{{ route('groups.items.store', $group) }}">
                                    @csrf
                                    <div class="inline">
                                        <div class="field w-280">
                                            <div class="label">Item</div>
                                            <select name="item_id">
                                                @foreach ($items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field w-220">
                                            <div class="label">Weight</div>
                                            <input type="number" step="0.0001" name="weight_value" placeholder="1">
                                        </div>
                                        <div class="field w-160">
                                            <div class="label">Unit</div>
                                            <select name="weight_unit_id">
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Tambah Item</button>
                                    </div>
                                </form>

                                <div class="divider"></div>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Weight (kg)</th>
                                             <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($group->items as $gi)
                                            <tr>
                                                <td>{{ $gi->item?->name }}</td>
                                                <td>{{ number_format($gi->weight_kg, 2) }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('groups.items.destroy', $gi) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger" type="submit">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($group->items->isEmpty())
                                            <tr>
                                                <td colspan="3" class="muted">Belum ada item.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                    @if ($production->groups->isEmpty())
                        <div class="muted">Belum ada golongan.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2>TAB (Split Batch)</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('productions.tabs.store', $production) }}">
                    @csrf
                    <div class="inline">
                        <div class="field w-220">
                            <div class="label">Nama TAB</div>
                            <input type="text" name="name" placeholder="TAB 1">
                        </div>
                        <div class="field w-220">
                            <div class="label">Ambil</div>
                            <input type="number" step="0.0001" name="input_weight_value" placeholder="1">
                        </div>
                        <div class="field w-160">
                            <div class="label">Unit</div>
                            <select name="input_weight_unit_id">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Buat TAB</button>
                    </div>
                </form>

                <div class="divider"></div>

                <div class="stack">
                    @foreach ($production->tabs as $tab)
                        <div class="panel">
                            <div class="panel-header">
                                <h2>{{ $tab->name }}</h2>
                                <span class="chip">Ambil: {{ number_format($tab->input_weight_kg, 2) }} kg</span>
                                <span class="chip">Sisa: {{ number_format($tab->remaining_weight_kg, 2) }} kg</span>
                                <form method="POST" action="{{ route('tabs.destroy', $tab) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                            <div class="panel-body">
                                <form method="POST" action="{{ route('tabs.items.store', $tab) }}">
                                    @csrf
                                    <div class="inline">
                                        <div class="field w-280">
                                            <div class="label">Item</div>
                                            <select name="item_id">
                                                @foreach ($items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field w-220">
                                            <div class="label">Weight</div>
                                            <input type="number" step="0.0001" name="weight_value" placeholder="1">
                                        </div>
                                        <div class="field w-160">
                                            <div class="label">Unit</div>
                                            <select name="weight_unit_id">
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Tambah Item</button>
                                    </div>
                                </form>

                                <div class="divider"></div>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Weight (gram)</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tab->items as $ti)
                                            <tr>
                                                <td>{{ $ti->item?->name }}</td>
                                                <td>{{ number_format($ti->weight_kg, 2) }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('tabs.items.destroy', $ti) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger" type="submit">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($tab->items->isEmpty())
                                            <tr>
                                                <td colspan="3" class="muted">Belum ada item.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                    @if ($production->tabs->isEmpty())
                        <div class="muted">Belum ada TAB.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
