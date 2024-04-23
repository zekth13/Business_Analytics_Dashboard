<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index(Request $request) //Create Report Index Page//
    {
        $controllerData = [
            //salesSummary
            'salesSummaryTableData' => $this->getSalesSummaryTableData($request)

            //productCategory

            //outeltPerformance

            //supplierPerformance

            //customizeReport
        ];
        return view('reports/index', compact('controllerData'));
    }
    ////////// SALES SUMMARY REPORT //////////
    public function getTotalSalesChartsData(Request $request) // Also for the datatable
    {
        $selectedTimeInterval = $request->query->get('timeInterval');
        $selectedYear = $request->query->get('year');
        $label = [];
        $result = [];

        function getTotalMonthlySalesData($year)
        {
            $query =  DB::select(DB::raw("SELECT Month, Total_Sales AS TotalSales FROM monthly_sales
                                        WHERE YEAR = '{$year}'"));

            $monthlySalesData = [];
            $array = array(
                "1" => 0,
                "2" => 0,
                "3" => 0,
                "4" => 0,
                "5" => 0,
                "6" => 0,
                "7" => 0,
                "8" => 0,
                "9" => 0,
                "10" => 0,
                "11" => 0,
                "12" => 0,
            );
            foreach ($query as $val) {
                $array[$val->Month] = (float)$val->TotalSales;
            }
            foreach ($array as $v) {
                array_push($monthlySalesData, (float)$v);
            }
            return $monthlySalesData;
        }
        if ($selectedTimeInterval == "monthly") {
            $label = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            array_push($result, $label, getTotalMonthlySalesData($selectedYear));
        } elseif ($selectedTimeInterval == "quarterly") {
            $totalMonthlySalesData = getTotalMonthlySalesData($selectedYear);
            $totalQuarterlySalesData = [];
            $k = 0;
            for ($i = 0; $i < 4; $i++) {
                $totalQuarterlySalesData[$i] = 0;
                for ($j = $k; $j < ($i + 1) * 3; $j++) {
                    $totalQuarterlySalesData[$i] += $totalMonthlySalesData[$j];
                }
                $k += 3;
            }
            $label = ["Q1", "Q2", "Q3", "Q4"];
            array_push($result, $label, $totalQuarterlySalesData);
        } elseif ($selectedTimeInterval == "annually") {
            $query = DB::select(DB::raw("SELECT Year, SUM(Total_Sales) AS TotalSales
                                    FROM monthly_sales
                                    GROUP BY Year"));

            $annualSalesData = [];
            $label = [];
            $startYear = 2014;
            for ($i = 0; $i < date("Y") - 2014 + 1; $i++) {
                $annualSalesData[$i] = 0;
                array_push($label, $startYear++);
            }
            foreach ($query as $val) {
                $annualSalesData[($val->Year) - 2014] = $val->TotalSales;
            }
            
            array_push($result, $label, $annualSalesData);
        }
        return $result;
    }


    public function getTotalProductQuantitySoldData(Request $request)
    {
        $selectedTimeInterval = $request->query->get('timeInterval');
        $selectedYear = $request->query->get('year');
        $label = [];
        $result = [];
        $tableTimeIntervalHeader = "";

        function getTotalMonthlyProductQuantitySoldData($year)
        {
            $query = DB::select(DB::raw("SELECT MONTH, SUM(QTY) as Quantity FROM product_monthly_sales
                                            WHERE YEAR = '{$year}'
                                            GROUP BY MONTH;"));

            $monthlyQuantitySoldData = [];
            $array = array(
                "1" => 0,
                "2" => 0,
                "3" => 0,
                "4" => 0,
                "5" => 0,
                "6" => 0,
                "7" => 0,
                "8" => 0,
                "9" => 0,
                "10" => 0,
                "11" => 0,
                "12" => 0,
            );
            foreach ($query as $val) {
                $array[$val->MONTH] = $val->Quantity;
            }
            foreach ($array as $v) {
                array_push($monthlyQuantitySoldData, (float)$v);
            }
            return $monthlyQuantitySoldData;
        }
        if ($selectedTimeInterval == "monthly") { // Selected time interval: Monthly
            $label = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $tableTimeIntervalHeader = "Month";
            array_push($result, $label, getTotalMonthlyProductQuantitySoldData($selectedYear),$tableTimeIntervalHeader);
        } elseif ($selectedTimeInterval == "quarterly") { // Selected time interval: Quarterly
            $label = ["Q1", "Q2", "Q3", "Q4"];
            $tableTimeIntervalHeader = "Quarter";
            $totalMonthlyQuantityData = getTotalMonthlyProductQuantitySoldData($selectedYear);
            $totalQuarterlyQuantityData = [];
            $k = 0;
            for ($i = 0; $i < 4; $i++) {
                $totalQuarterlyQuantityData[$i] = 0;
                for ($j = $k; $j < ($i + 1) * 3; $j++) {
                    $totalQuarterlyQuantityData[$i] += $totalMonthlyQuantityData[$j];
                }
                $k += 3;
            }
            array_push($result, $label, $totalQuarterlyQuantityData, $tableTimeIntervalHeader);
        } elseif ($selectedTimeInterval == "annually") { // Selected time interval: Annually
            $query = DB::select(DB::raw("SELECT YEAR, SUM(QTY) AS Quantity FROM product_monthly_sales
                                        GROUP BY YEAR;"));

            $annualQuantityData = [];
            $label = [];
            $tableTimeIntervalHeader = "Year";
            $startYear = 2014;
            for ($i = 0; $i < date("Y") - 2014 + 1; $i++) {
                $annualQuantityData[$i] = 0;
                array_push($label, $startYear++);
            }
            foreach ($query as $val) {
                $annualQuantityData[($val->YEAR) - 2014] = $val->Quantity;
            }
            array_push($result, $label, $annualQuantityData, $tableTimeIntervalHeader);
        }
        return $result;
    }

    public function getSalesSummaryTableData()
    {
        $query = DB::select(DB::raw("SELECT YEAR, MONTH, SUM(TOTAL_SALES) as TOTAL_SALES, SUM(QTY) as QTY
                                        FROM product_monthly_sales
                                        GROUP BY YEAR, MONTH
                                        ORDER BY YEAR DESC, MONTH;
        "));
        $result = [];
        $yearData = [];
        $monthData = [];
        $totalSalesData = [];
        $quantitySoldData = [];

        foreach ($query as $val) {
            array_push($yearData, $val->YEAR);
            array_push($monthData, $val->MONTH);
            array_push($totalSalesData, $val->TOTAL_SALES);
            array_push($quantitySoldData, $val->QTY);
        }
        array_push($result, $yearData, $monthData, $totalSalesData, $quantitySoldData);
        //dd($result);
        return $result;
    }

    ////////// PRODUCT CATEGORY REPORT //////////
    public function productCategoryReport(Request $request)
    {
        return view('reports/product_category');
    }
    ////////// OUTLET PERFORMANCE REPORT //////////
    public function outletPerformanceReport(Request $request)
    {
        return view('reports/outlet_performance');
    }
    ////////// SUPPLIER PERFORMANCE REPORT //////////
    public function supplierPerformanceReport(Request $request)
    {
        return view('reports/supplier_performance');
    }
    ////////// CUSATOMIZE REPORT //////////
    public function customReport(Request $request)
    {
        return view('reports/custom_report');
    }
}
