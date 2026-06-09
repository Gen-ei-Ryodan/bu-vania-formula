<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\Location;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->get('report_type', 'default');

        if ($reportType === 'history_konsep' || $reportType === 'konsep_aktif') {
            return $this->conceptReport($request, $reportType);
        }

        $query = Production::query()->with('concept')->orderByDesc('id');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('production_type')) {
            $query->where('production_type', $request->production_type);
        }

        if ($request->filled('mix_date_from')) {
            $query->whereDate('mix_date', '>=', $request->mix_date_from);
        }

        if ($request->filled('mix_date_to')) {
            $query->whereDate('mix_date', '<=', $request->mix_date_to);
        }

        if ($request->filled('start_date_from')) {
            $query->whereDate('start_date', '>=', $request->start_date_from);
        }

        if ($request->filled('start_date_to')) {
            $query->whereDate('start_date', '<=', $request->start_date_to);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('cage')) {
            $query->where('cage', 'like', '%' . $request->cage . '%');
        }

        $productions = $query->get();
        $locations = Location::orderBy('name')->get();

        return view('dashboard.reports.index', compact('productions', 'locations', 'request'));
    }

    protected function conceptReport(Request $request, string $type)
    {
        $query = Production::query()->with(['concept'])->orderBy('start_date');

        if ($type === 'konsep_aktif') {
            $query->where('is_active', true);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('start_date_from')) {
            $query->whereDate('start_date', '>=', $request->start_date_from);
        }

        if ($request->filled('start_date_to')) {
            $query->whereDate('start_date', '<=', $request->start_date_to);
        }

        if ($request->filled('concept_id')) {
            $query->where('concept_id', $request->concept_id);
        }

        $productions = $query->get();
        $locations = Location::orderBy('name')->get();

        $concepts = \App\Models\Concept::orderBy('name')->get();

        $title = $type === 'konsep_aktif' ? 'Laporan Konsep Aktif per Tanggal' : 'Laporan History Konsep per Tanggal';

        return view('dashboard.reports.concept_report', compact('productions', 'locations', 'request', 'title', 'concepts', 'type'));
    }

    public function pdf(Request $request)
    {
        $reportType = $request->get('report_type', 'default');

        if ($reportType === 'history_konsep' || $reportType === 'konsep_aktif') {
            return $this->conceptReportPdf($request, $reportType);
        }

        $query = Production::query()->with([
            'concept',
            'items.item',
            'groups.items.item',
            'groups.items.inputUnit',
            'tabs.items.item',
            'tabs.items.inputUnit',
        ])->orderByDesc('id');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('production_type')) {
            $query->where('production_type', $request->production_type);
        }

        if ($request->filled('mix_date_from')) {
            $query->whereDate('mix_date', '>=', $request->mix_date_from);
        }

        if ($request->filled('mix_date_to')) {
            $query->whereDate('mix_date', '<=', $request->mix_date_to);
        }

        if ($request->filled('start_date_from')) {
            $query->whereDate('start_date', '>=', $request->start_date_from);
        }

        if ($request->filled('start_date_to')) {
            $query->whereDate('start_date', '<=', $request->start_date_to);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('cage')) {
            $query->where('cage', 'like', '%' . $request->cage . '%');
        }

        $productions = $query->get();

        $pdf = Pdf::loadView('dashboard.reports.pdf', compact('productions'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-' . now()->format('Y-m-d') . '.pdf');
    }

    protected function conceptReportPdf(Request $request, string $type)
    {
        $query = Production::query()->with(['concept'])->orderBy('start_date');

        if ($type === 'konsep_aktif') {
            $query->where('is_active', true);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('start_date_from')) {
            $query->whereDate('start_date', '>=', $request->start_date_from);
        }

        if ($request->filled('start_date_to')) {
            $query->whereDate('start_date', '<=', $request->start_date_to);
        }

        if ($request->filled('concept_id')) {
            $query->where('concept_id', $request->concept_id);
        }

        $productions = $query->get();

        $title = $type === 'konsep_aktif' ? 'Laporan Konsep Aktif per Tanggal' : 'Laporan History Konsep per Tanggal';
        $filename = $type === 'konsep_aktif' ? 'konsep-aktif-' : 'history-konsep-';

        $pdf = Pdf::loadView('dashboard.reports.concept_report_pdf', compact('productions', 'title'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename . now()->format('Y-m-d') . '.pdf');
    }
}
