<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Pengobatan: {{ $production->name }}</title>
        <style>
            body { font-family: sans-serif; font-size: 11px; color: #222; padding: 20px; }
            h1 { font-size: 16px; margin-bottom: 4px; }
            h2 { font-size: 13px; margin: 14px 0 6px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
            th, td { padding: 5px 8px; text-align: left; border: 1px solid #ddd; }
            th { background: #f5f5f5; font-size: 10px; text-transform: uppercase; }
            .info { display: flex; flex-wrap: wrap; gap: 16px; margin: 8px 0 16px; }
            .info > div { font-size: 11px; }
            .info strong { display: block; font-size: 13px; }
            .chip { display: inline-block; background: #e8f4fd; padding: 2px 8px; border-radius: 4px; font-size: 10px; }
            .cb-col { width: 60px; text-align: center; }
            .ttd-box { border: 1px solid #ddd; padding: 6px; margin-top: 20px; display: inline-block; min-width: 200px; }
            .ttd-box div { font-size: 10px; color: #666; text-align: center; }
            .ttd-space { height: 50px; }
        </style>
    </head>
    <body>
        <h1>{{ $production->name }}</h1>
        <div class="info">
            <div>
                <span>Resep</span>
                <strong>{{ $production->concept?->name ?? '-' }}</strong>
            </div>
            <div>
                <span>Kapasitas</span>
                <strong>{{ formatWeight($production->target_weight_kg) }} kg</strong>
            </div>
            <div>
                <span>Pengobatan Hari Ke</span>
                <strong>{{ $production->treatment_day ?? '-' }}</strong>
            </div>
            <div>
                <span>Waktu</span>
                <strong><span class="chip">{{ $production->treatment_time ?? '-' }}</span></strong>
            </div>
            @if ($production->treatment_duration_days)
            <div>
                <span>Lama Pengobatan</span>
                <strong>{{ $production->treatment_duration_days }} hari</strong>
            </div>
            @endif
            <div>
                <span>Tanggal Campur</span>
                <strong>{{ $production->mix_date?->format('d-m-Y') ?? '-' }}</strong>
            </div>
            <div>
                <span>Tanggal Mulai Pakai Konsep</span>
                <strong>{{ $production->start_date?->format('d-m-Y') ?? '-' }}</strong>
            </div>
            <div>
                <span>Durasi</span>
                <strong>{{ $production->is_forever ? 'Selamanya' : $production->duration_days.' hari' }}</strong>
            </div>
            <div>
                <span>Status</span>
                <strong>{{ $production->is_active ? 'Aktif' : 'Tidak Aktif' }}</strong>
            </div>
            <div>
                <span>Lokasi</span>
                <strong>{{ $production->location ?? '-' }}</strong>
            </div>
            <div>
                <span>Kandang</span>
                <strong>{{ $production->cage ?? '-' }}</strong>
            </div>
        </div>

        @if ($production->notes)
            <p><strong>Catatan:</strong> {{ $production->notes }}</p>
        @endif

        <h2>Snapshot Item</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Berat (kg)</th>
                    <th>Sumber</th>
                    <th class="cb-col">Cek &#9744;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($production->items as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->item?->name }}</td>
                        <td>{{ formatWeight($row->weight_kg) }}</td>
                        <td><span class="chip">{{ $row->source }}</span></td>
                        <td class="cb-col"></td>
                    </tr>
                @endforeach
                @if ($production->items->isEmpty())
                    <tr>
                        <td colspan="5">Belum ada snapshot item.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <h2>Golongan</h2>
        @foreach ($production->groups as $group)
            <div><strong>{{ $group->name }}</strong></div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th style="width: 120px;">Berat (kg)</th>
                        <th style="width: 80px;">Dosis</th>
                        <th class="cb-col">Cek &#9744;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($group->items as $i => $gi)
                        @php
                            $displayW = $gi->weight_input_value && $gi->inputUnit
                                ? formatWeight($gi->weight_input_value).' '.$gi->inputUnit->name
                                : formatWeight($gi->weight_kg).' kg';
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $gi->item?->name }}</td>
                            <td>{{ $displayW }}</td>
                            <td>{{ $gi->is_dosis ? 'Dosis' : '-' }}</td>
                            <td class="cb-col"></td>
                        </tr>
                    @endforeach
                    @if ($group->items->isEmpty())
                        <tr>
                            <td colspan="5">Belum ada item.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
        @if ($production->groups->isEmpty())
            <div>Belum ada golongan.</div>
        @endif

        <h2>TAB (Split Batch)</h2>
        @foreach ($production->tabs as $tab)
            <div>
                <strong>{{ $tab->name }}</strong>
                (Ambil: {{ formatWeight($tab->input_weight_kg) }} kg, Sisa: {{ formatWeight($tab->remaining_weight_kg) }} kg)
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th style="width: 120px;">Berat (kg)</th>
                        <th style="width: 80px;">Dosis</th>
                        <th class="cb-col">Cek &#9744;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tab->items as $i => $ti)
                        @php
                            $displayW = $ti->weight_input_value && $ti->inputUnit
                                ? formatWeight($ti->weight_input_value).' '.$ti->inputUnit->name
                                : formatWeight($ti->weight_kg).' kg';
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $ti->item?->name }}</td>
                            <td>{{ $displayW }}</td>
                            <td>{{ $ti->is_dosis ? 'Dosis' : '-' }}</td>
                            <td class="cb-col"></td>
                        </tr>
                    @endforeach
                    @if ($tab->items->isEmpty())
                        <tr>
                            <td colspan="5">Belum ada item.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
        @if ($production->tabs->isEmpty())
            <div>Belum ada TAB.</div>
        @endif

        <br><br>
        <table style="width: 100%; border: 0;">
            <tr>
                <td style="width: 50%; border: 0; text-align: center; padding-top: 20px;">
                    <div class="ttd-box">
                        <div>Penimbang</div>
                        <div class="ttd-space"></div>
                        <div>( __________________ )</div>
                    </div>
                </td>
                <td style="width: 50%; border: 0; text-align: center; padding-top: 20px;">
                    <div class="ttd-box">
                        <div>Mengetahui</div>
                        <div class="ttd-space"></div>
                        <div>( __________________ )</div>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>
