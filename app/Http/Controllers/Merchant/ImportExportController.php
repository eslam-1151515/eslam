<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Services\ImportExportService;
use App\Http\Requests\ImportRequest;
use App\Http\Requests\ExportRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ImportExportController extends Controller
{
    protected $importExportService;

    public function __construct(ImportExportService $importExportService)
    {
        $this->importExportService = $importExportService;
    }

    /**
     * عرض صفحة الاستيراد والتصدير
     */
    public function index()
    {
        return Inertia::render('Merchant/ImportExport/Index', [
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
                'import_result' => session('import_result')
            ]
        ]);
    }

    /**
     * استيراد المنتجات
     */
    public function import(ImportRequest $request)
    {
        $validated = $request->validated();

        $file = $request->file('file');
        $source = $request->source;

        try {
            $result = $this->importExportService->importProducts($file->getRealPath(), $source);
            
            return redirect()->back()->with([
                'success' => 'تمت عملية الاستيراد بنجاح.',
                'import_result' => $result
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    /**
     * تصدير البيانات
     */
    public function export(ExportRequest $request)
    {
        $validated = $request->validated();

        $type = $request->type;
        $csvContent = '';
        $filename = '';

        switch ($type) {
            case 'products':
                $csvContent = $this->importExportService->exportProducts();
                $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';
                break;
            case 'customers':
                $csvContent = $this->importExportService->exportCustomers();
                $filename = 'customers_export_' . date('Y-m-d_H-i-s') . '.csv';
                break;
            case 'orders':
                $csvContent = $this->importExportService->exportOrders();
                $filename = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';
                break;
        }

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ]);
    }

    /**
     * تحميل قالب الاستيراد
     */
    public function downloadTemplate()
    {
        $headers = [
            'اسم المنتج',
            'الوصف',
            'القسم',
            'السعر الحالي',
            'السعر قبل الخصم',
            'المخزون',
            'حد المخزون المنخفض',
            'نوع الشحن',
            'رابط الصورة',
            'المقاسات',
            'الألوان'
        ];

        $sampleRow = [
            'تيشيرت قطن كاجوال',
            'تيشيرت قطن مميز بألوان صيفية مريحة',
            'ملابس رجالي',
            '250.00',
            '350.00',
            '50',
            '5',
            'free',
            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500',
            'S,M,L,XL',
            'أزرق,أسود,أبيض'
        ];

        $output = fopen('php://temp', 'r+');
        fwrite($output, "\xEF\xBB\xBF"); // BOM
        fputcsv($output, $headers);
        fputcsv($output, $sampleRow);
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="fastorder_products_template.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ]);
    }
}
