<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomerproductvisitExcelReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $categoryId = '';
    protected $customerId = '';
    protected $productId = '';
    protected $year = '';
    protected $startDate = '';
    protected $endDate = '';

    function __construct($categoryId = '', $customerId = '', $productId = '', $year = '', $startDate = '', $endDate = '') {
        $this->categoryId = $categoryId;
        $this->customerId = $customerId;
        $this->productId = $productId;
        $this->year = $year;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {

        $product_customers =  DB::table('productvisit')
                            ->select('productcategories.categoryId','categories.category','products.productId','products.productName as productName', 'productvisit.userId AS customerId', 'users.name as name', 'users.phone as phone')
                            ->leftJoin('products', 'products.productId', 'productvisit.productId')
                            ->leftJoin('users', 'users.id', 'productvisit.userId')
                            ->leftJoin('productcategories', 'productcategories.productId', 'products.productId')
                            ->leftJoin('categories', 'productcategories.categoryId', 'categories.categoryId')
                            ->distinct()
                            ->get();
        $custproductvisits = DB::table('productvisit')
                            ->selectRaw('productvisit.productvisitId, productvisit.productId, productvisit.userId AS customerId, YEAR(productvisit.created_at) AS visitYear, DATE_FORMAT(productvisit.created_at, "%Y-%m-%d") as visitDate')
                            ->get();

                            // dd($custproductvisits);

        $report_data = collect([]);
        foreach ($product_customers as $i => $product_customer) {

            $single_custproductvisits_data = $custproductvisits->where('productId', $product_customer->productId)->where('customerId', $product_customer->customerId);

            $custFirstProductVisitDate = $single_custproductvisits_data->pluck('visitDate')->first() ?? '';
            $custLastProductVisitDate = $single_custproductvisits_data->sortByDesc('productvisitId')->pluck('visitDate')->first() ?? '';

            ($custFirstProductVisitDate==$custLastProductVisitDate) ?  $custLastProductVisitDate ='' : null;

            $report_data[$i] = [
                'categoryId' => $product_customer->categoryId,
                'category' => $product_customer->category,
                'customerId' => $product_customer->customerId,
                'name' => $product_customer->name,
                'phone' => $product_customer->phone,
                'productId' => $product_customer->productId,
                'productName' => $product_customer->productName,
                'productPicturePath' => getProductImage($product_customer->productId),
                'custFirstProductVisitDate' => $custFirstProductVisitDate,
                'custLastProductVisitDate' => $custLastProductVisitDate,
            ];
        }

        // ==================category restriction================
        if (isset($this->categoryId) && $this->categoryId!=null)
        {
            $categoryId = $this->categoryId;

            // -1 = all
            // no need to code

            // 1 to 1000 = categoryId
            if ($categoryId>0 && $categoryId<=1000)
            {
                $report_data = $report_data->where('categoryId', $categoryId);
            }
            // >= 1001 = giftTypeId
            if ($categoryId>1000 )
            {
                $giftTypeId = $categoryId%1000;
                $giftproducts = DB::table('giftproducts')->where('giftTypeId', $giftTypeId)->pluck('productId');
                $report_data = $report_data->whereIn('productId', $giftproducts);
            }
        }
        // ==================category restriction================

        // ==================customer restriction================
        if (isset($this->customerId) && $this->customerId!=null)
        {
            $customerId = $this->customerId;
            $report_data = $report_data->where('customerId', $customerId);
        }
        // ==================customer restriction================


        // ==================product restriction================
        if (isset($this->productId) && $this->productId!=null)
        {
            $productId = $this->productId;
            $report_data = $report_data->where('productId', $productId);
        }
        // ==================product restriction================


        $report_data = $report_data->map(function ($record, $i) use( $custproductvisits) {

            $custproductvisits = $custproductvisits->where('productId', $record['productId']);

            $cust_prod_hit_count = 0;
            $all_cust_prod_hit_count = 0;

            // dd($custproductvisits);
            // ==================startDate restriction================
            if (isset($this->startDate) && $this->startDate!=null)
            {
                $custproductvisits = $custproductvisits->where('visitDate', '>=', startDate);
            }
            // ==================startDate restriction================


            // ==================endDate restriction================
            if (isset($this->endDate) && $this->endDate!=null)
            {
                $custproductvisits = $custproductvisits->where('visitDate', '<=', $this->endDate);
            }
            // ==================endDate restriction================

            // ==================year restriction================
            if (isset($this->year) && $this->year!=null)
            {
                $custproductvisits = $custproductvisits->where('visitYear', $this->year);
            }
            // ==================year restriction================

            // dd($custproductvisits);


            $cust_prod_hit_count = $custproductvisits->where('customerId', $record['customerId'])->count('productId');
            $all_cust_prod_hit_count = $custproductvisits->count('productId');

            $record['cust_prod_hit_count'] = $cust_prod_hit_count;
            $record['all_cust_prod_hit_count'] = $all_cust_prod_hit_count;

            return $record;
        });


        // ==================sorting restriction================
        if (isset($this->sortBy) && $this->sortBy!=null)
        {
            $sortBy = $this->sortBy;
            switch ($sortBy) {
                case "all_cust_prod_hit_count_asc":
                    $report_data = $report_data->sortBy('all_cust_prod_hit_count');
                    break;
                case "all_cust_prod_hit_count_desc":
                    $report_data = $report_data->sortByDesc('all_cust_prod_hit_count');
                    break;
                case "date_asc":
                    $report_data = $report_data->sortBy('custFirstProductVisitDate');
                    break;
                default:
                    $report_data = $report_data->sortByDesc('custLastProductVisitDate');
            }
        }
        // ==================sorting restriction================

        // dd($report_data);


        $data = collect([]);

        $data = $report_data->map(function ($item, $key) {
            return [
                'customerId' => $item['customerId'],
                'category' => $item['category'],
                'name' => $item['name'],
                'phone' => $item['phone'],
                'productName' => $item['productName'],
                'cust_prod_hit_count' => $item['cust_prod_hit_count'],
                'all_cust_prod_hit_count' => $item['all_cust_prod_hit_count'],
                'custFirstProductVisitDate' => \YmdTodmY($item['custFirstProductVisitDate']),
                'custLastProductVisitDate' => \YmdTodmY($item['custLastProductVisitDate']),
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
	           'Customer Id'  ,
               'Category',
               'Customer Name',
               'Customer Phone',
               'Product Name',
               'Customer Product Hit Count',
               'Total Product Hit Count',
               'First Visit Date',
               'Last Visit Date',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }

}
