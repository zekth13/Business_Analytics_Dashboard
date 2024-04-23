<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuppliersController extends Controller
{
    public function suppliers(request $request)
    {
        $charts = [
            'supplierNameData' => $this->getSupplierNameAndNo($request),
            'top20SupplierData' => $this->getTop10Supplier($request),
            'supplierProductSalesData' => $this->getSupplierProductSales($request),
            'supplierNoAndNameData' => $this->getAllSupplierDetails()
        ];
        return view("suppliers/index", compact('charts'));
    }

    public function getSupplierNameAndNo(request $request)
    {
        $state = $request->query->get('state');

        if (!isset($state) or $state == "*") {
            $query =  DB::select(DB::raw("SELECT SUP_NA as SupplierName, SUP_NO as SupplierNumber
                        FROM suppliers"));
        } elseif ($state == "NULL") {
            $query =  DB::select(DB::raw("SELECT SUP_NA as SupplierName, SUP_NO as SupplierNumber
                        FROM suppliers WHERE STATE_NA IS NULL"));
        } else {
            $query =  DB::select(DB::raw("SELECT SUP_NA as SupplierName, SUP_NO as SupplierNumber
                        FROM suppliers WHERE STATE_NA LIKE '{$state}'"));
        }
        $supplierNoData = [];
        $supplierNameData = [];
        foreach ($query as $val) {
            array_push($supplierNoData, $val->SupplierNumber);
            array_push($supplierNameData, $val->SupplierName);
        }
        $queryData = [$supplierNoData, $supplierNameData];
        return $queryData;
    }

    public function getTop10Supplier(request $request)
    {
        $year = $request->query->get('year');
        $month = $request->query->get('month');
        $state = $request->query->get('state');
        if (!isset($year)) {
            $year = 2020;
        }

        if (!isset($month)) {   //select all months
            if (!isset($state) or $state == "*") {  //select for all states
                $query =  DB::select(DB::raw("SELECT
                            SUP_NA AS SupplierName, SUM(TOTAL_SALES) AS TotalSales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year}
                            GROUP BY SupplierName ORDER BY TotalSales DESC
                            LIMIT 10"));
            } elseif ($state == 'NULL') {   //select for null state
                $query =  DB::select(DB::raw("SELECT
                            SUP_NA AS SupplierName, SUM(TOTAL_SALES) AS TotalSales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year} AND STATE_NA IS NULL
                            GROUP BY SupplierName ORDER BY TotalSales DESC
                            LIMIT 10"));
            } else {    //select for selected state
                $query =  DB::select(DB::raw("SELECT
                            SUP_NA AS SupplierName, SUM(TOTAL_SALES) AS TotalSales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year} AND STATE_NA = '{$state}'
                            GROUP BY SupplierName ORDER BY TotalSales DESC
                            LIMIT 10"));
            }
        } else {  //select for selected months
            if (!isset($state) or $state == "*") {  //select for all states
                $query =  DB::select(DB::raw("SELECT
                            SUP_NA AS SupplierName, SUM(TOTAL_SALES) AS TotalSales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year} AND MONTH = {$month}
                            GROUP BY SupplierName ORDER BY TotalSales DESC
                            LIMIT 10"));
            } elseif ($state == 'NULL') {   //select for null state
                $query =  DB::select(DB::raw("SELECT
                            SUP_NA AS SupplierName, SUM(TOTAL_SALES) AS TotalSales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year} AND MONTH = {$month} AND STATE_NA IS NULL
                            GROUP BY SupplierName ORDER BY TotalSales DESC
                            LIMIT 10"));
            } else {    //selected state
                $query =  DB::select(DB::raw("SELECT
                            SUP_NA AS SupplierName, SUM(TOTAL_SALES) AS TotalSales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year} AND MONTH = {$month} AND STATE_NA = '{$state}'
                            GROUP BY SupplierName ORDER BY TotalSales DESC
                            LIMIT 10"));
            }
        }

        $top10SupplierNameData = [];
        $top10SupplierTotalSalesData = [];
        foreach ($query as $val) {
            array_push($top10SupplierNameData, $val->SupplierName);
            array_push($top10SupplierTotalSalesData, $val->TotalSales);
        }
        $queryData = [$top10SupplierNameData, $top10SupplierTotalSalesData];
        return $queryData;
    }

    public function getAllSupplierDetails() //for supplier select dropdown
    {
        $query =  DB::select(DB::raw("SELECT suppliers.SUP_NO AS Supplier_No, suppliers.SUP_NA AS Supplier_Name, suppliers.STATE_NA AS State_Name
                                FROM suppliers
                                ORDER BY Supplier_Name"));
        $supplierNoData = [];
        $supplierNameData = [];
        $supplierStateData = [];
        foreach ($query as $val) {
            if ($val->Supplier_Name == "CLOSED") {
                continue;
            }
            array_push($supplierNoData, $val->Supplier_No);
            array_push($supplierNameData, $val->Supplier_Name);
            if (is_null($val->State_Name)) {
                array_push($supplierStateData, "N/A");
            } else {
                array_push($supplierStateData, $val->State_Name);
            }
        }
        $queryData = [$supplierNoData, $supplierNameData, $supplierStateData];
        return $queryData;
    }

    public function getSupplierProductSales(request $request)
    {
        $supplierName = $request->query->get('supplierName');   //contains SUP_NO value (string)
        $year = $request->query->get('year');
        $month = $request->query->get('month');

        if (!isset($year)) {
            $year = 2015;
        }

        DB::statement("SET SQL_MODE=''"); //to avoid (SQLSTATE[42000]: Syntax error or access violation: 1055), instead of changing ('strict' => false) in (config\database.php --> "mysql")
        
        if (!isset($supplierName)) {    //select for all suppliers
            if (isset($month)) {    //select for selected month
                $query =  DB::select(DB::raw("SELECT
                            GOO_NO AS Product_No, GOO_NA AS Product_Name, SUM(QTY) AS Sales_Quantity, SUM(TOTAL_SALES) AS Total_Sales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year} AND MONTH = {$month}
                            GROUP BY Product_Name
                            ORDER BY Total_Sales DESC"));
            } else { //select for all months
                $query =  DB::select(DB::raw("SELECT
                            GOO_NO AS Product_No, GOO_NA AS Product_Name, SUM(QTY) AS Sales_Quantity, SUM(TOTAL_SALES) AS Total_Sales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year}
                            GROUP BY Product_Name
                            ORDER BY Total_Sales DESC"));
            }
        } else {    //select for selected supplier
            if (isset($month)) {    //select for selected month
                $query =  DB::select(DB::raw("SELECT
                            GOO_NO AS Product_No, GOO_NA AS Product_Name, SUM(QTY) AS Sales_Quantity, SUM(TOTAL_SALES) AS Total_Sales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year} AND MONTH = {$month} AND SUP_NO = '{$supplierName}'
                            GROUP BY Product_Name
                            ORDER BY Total_Sales DESC"));
            } else {  //select for all months
                $query =  DB::select(DB::raw("SELECT
                            GOO_NO AS Product_No, GOO_NA AS Product_Name, SUM(QTY) AS Sales_Quantity, SUM(TOTAL_SALES) AS Total_Sales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year} AND SUP_NO = '{$supplierName}'
                            GROUP BY Product_Name
                            ORDER BY Total_Sales DESC"));
            }
        }

        $productNoData = [];
        $productNameData = [];
        $salesQuantityData = [];
        $totalSalesData = [];
        foreach ($query as $val) {
            array_push($productNoData, $val->Product_No);
            array_push($productNameData, $val->Product_Name);
            array_push($salesQuantityData, number_format(($val->Sales_Quantity), 0, '.', ','));
            array_push($totalSalesData, number_format(($val->Total_Sales), 2, '.', ','));
        }
        $queryData = [$productNoData, $productNameData, $salesQuantityData, $totalSalesData];
        return $queryData;
    }
}
