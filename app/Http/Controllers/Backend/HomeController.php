<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Reports\Sales\SalesReport;
use Reports\Factory\ReportFactory;
use App\Http\Controllers\Controller;


class HomeController extends Controller
{
    
    public function index()
    {

        $report = ReportFactory::make(SalesReport::class);
        
        $data = $report->getData();
        $data['barGraph'] = $report->getMonthly();
        
        $data['monthly_sales'] = $report->getCurrentMonth();
        
        $saleable = $report->getMostSaleableCatalog();
        
        $length = count($saleable);
        $most_sales = [
            'labels' => [],
            'series' => []
        ];
        
        for($a = 0; $a < $length; $a++){
            
            $most_sales['labels'][] = $saleable[$a]['catalog_name'];
            $most_sales['series'][] = $saleable[$a]['count'];
        }
        $data['most_sales'] = count($most_sales['labels']) > 0 ? $most_sales : [];
        $data['linegraph'] = $report->getConvertedAndUnpaid();
        $view_data = [
            'page_title' => 'Dashboard',
            'warning_message' => 'DISPLAY ONLY. Dashboard below is for sample purposes.'
        ];
        $view_data = array_merge($view_data, $data);
        
        return view('backend.pages.dashboard.reports', $view_data);
    }
}
