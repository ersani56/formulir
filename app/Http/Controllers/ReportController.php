<?php

namespace App\Http\Controllers;

use App\Models\ArsipPeg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function showReportPage()
    {
        return view('report');
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'export_format' => 'required|in:pdf,excel'
        ]);

        $query = ArsipPeg::query();

        // Filter by date range
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        if ($request->export_format === 'pdf') {
            return $this->exportPDF($data, $request->start_date, $request->end_date);
        } else {
            return $this->exportExcel($data, $request->start_date, $request->end_date);
        }
    }

    private function exportPDF($data, $startDate, $endDate)
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L'
        ]);

        $html = view('reports.pdf', [
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now()->format('d/m/Y H:i:s')
        ])->render();

        $mpdf->WriteHTML($html);

        $filename = 'report_arsip_pegawai_' . now()->format('Y_m_d_His') . '.pdf';
        return $mpdf->Output($filename, 'D');
    }

    private function exportExcel($data, $startDate, $endDate)
    {
        $filename = 'report_arsip_pegawai_' . now()->format('Y_m_d_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'LAPORAN DATA ARSIP PEGAWAI');
        $sheet->setCellValue('A2', 'BKPSDM - Badan Kepegawaian dan Pengembangan Sumber Daya Manusia');
        $sheet->setCellValue('A3', 'Periode: ' .
            ($startDate ? Carbon::parse($startDate)->format('d/m/Y') : 'Semua Data') . ' - ' .
            ($endDate ? Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang'));
        $sheet->setCellValue('A4', 'Dibuat pada: ' . now()->format('d/m/Y H:i:s'));

        // Set column headers
        $headers = ['No', 'NIP', 'Email', 'DRH', 'SKCPNS', 'SKPNS', 'SPMT', 'Tanggal Upload', 'Terakhir Update'];
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '6', $header);
            $sheet->getStyle($column . '6')->getFont()->setBold(true);
            $column++;
        }

        // Fill data
        $row = 7;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->nip);
            $sheet->setCellValue('C' . $row, $item->email);
            $sheet->setCellValue('D' . $row, $item->drh_path ? '✅' : '❌');
            $sheet->setCellValue('E' . $row, $item->skcpns_path ? '✅' : '❌');
            $sheet->setCellValue('F' . $row, $item->skpns_path ? '✅' : '❌');
            $sheet->setCellValue('G' . $row, $item->spmt_path ? '✅' : '❌');
            $sheet->setCellValue('H' . $row, $item->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('I' . $row, $item->updated_at->format('d/m/Y H:i'));
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add borders
        $sheet->getStyle('A6:I' . ($row-1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function getStatistics()
    {
        $totalPegawai = ArsipPeg::count();
        $totalWithFiles = ArsipPeg::where(function($query) {
            $query->whereNotNull('drh_path')
                  ->orWhereNotNull('skcpns_path')
                  ->orWhereNotNull('skpns_path')
                  ->orWhereNotNull('spmt_path');
        })->count();

        $fileStats = [
            'drh' => ArsipPeg::whereNotNull('drh_path')->count(),
            'skcpns' => ArsipPeg::whereNotNull('skcpns_path')->count(),
            'skpns' => ArsipPeg::whereNotNull('skpns_path')->count(),
            'spmt' => ArsipPeg::whereNotNull('spmt_path')->count(),
        ];

        $recentUploads = ArsipPeg::with(['files'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'total_pegawai' => $totalPegawai,
            'total_with_files' => $totalWithFiles,
            'file_stats' => $fileStats,
            'recent_uploads' => $recentUploads
        ]);
    }
}
