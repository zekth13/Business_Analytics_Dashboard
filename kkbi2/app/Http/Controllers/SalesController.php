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

class SalesController extends Controller
{

    /**
     * Function to hold all related functions to be viewed and returned
     *
     * @param  string  Request
     * @return view & array[array[],array[],array[]]
     */
    public function index(Request $request) //SALES SUMMARY PAGE//
    {
        $charts = [
            'monthlySalesData' => $this->getSalesSummaryPerMonth($request),
            'monthlySalesGrowthData' => $this->getMonthlySalesGrowth($request),
            'anualSalesAndGrowthData' => $this->getAnnualSalesAndGrowth(),
            'getTotalStateYearly' => $this->getTotalStateYearly(),
            'getDepartmentTotalSalesYearly' => $this->getDepartmentTotalSalesYearly(),
            'getMonthlySales' => $this->getMonthlySales()
        ];
        return view("sales/index", compact('charts'));
    }

    /**
     * Get monthly sales (all months) from a selected year.
     *
     * @param  string  Request
     * @return float[]
     */
    public function getSalesSummaryPerMonth(Request $request)
    {
        $year = $request->query->get('year');
        if (!isset($year)) {
            $year = 2020;
        }

        //Monthly Sales Chart
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

    /**
     * Get monthly growth (%) from a selected year.
     *
     * @param  string  Request
     * @return float[]
     */
    public function getMonthlySalesGrowth(Request $request)
    {
        $year = $request->query->get('year');
        if (!isset($year)) {
            $year = 2020;
        }

        //Monthly Sales Data
        $query =  DB::select(DB::raw("SELECT Month, Total_Sales AS TotalSales FROM monthly_sales
                                WHERE YEAR = '{$year}'"));

        if ($year > 2020) {   //get last year's Dec total sales to be compard  to the current year's Jan sale
            $lastYearDecemberSale = 0;
            $queryLastDec =  DB::select(DB::raw("SELECT Total_Sales AS TotalSales
                                            FROM monthly_sales
                                            WHERE Year = ($year-1) AND Month = 12"));

            if (isset($queryLastDec[0])) {
                $lastYearDecemberSale = $queryLastDec[0]->TotalSales;
            }
        } else {
            $lastYearDecemberSale = 0;
        }

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
        $monthlyGrowth = [];
        for ($i = 0; $i < 12; $i++) {
            if ($i == 0) {
                if ($lastYearDecemberSale == 0) { //avoid division by zero error
                    array_push($monthlyGrowth, (float) 0);
                } else {
                    $curentMonthGrowth = (float) ($monthlySalesData[$i] - $lastYearDecemberSale) / $lastYearDecemberSale * 100;
                    array_push($monthlyGrowth, $curentMonthGrowth);
                }
            } else {
                if ($monthlySalesData[$i - 1] == 0) { //avoid division by zero error
                    array_push($monthlyGrowth, (float) 0);
                } else {
                    $curentMonthGrowth = (float) ($monthlySalesData[$i] - $monthlySalesData[$i - 1]) / $monthlySalesData[$i - 1] * 100;
                    array_push($monthlyGrowth, $curentMonthGrowth);
                }
            }
        }
        return $monthlyGrowth;
    }

    /**
     * Get annual (all years) sales and growth (%)
     *
     * @return array[int[], float[]]
     */
    public function getAnnualSalesAndGrowth()   //for annual sales charts
    {
        $query = DB::select(DB::raw("SELECT Year, SUM(Total_Sales) AS TotalSales
                                    FROM monthly_sales
                                    GROUP BY Year"));

        $annualSalesData = [];

        for ($i = 0; $i < date("Y") - 2014; $i++) {
            $annualSalesData[$i] = 0;
        }

        foreach ($query as $val) {
            $annualSalesData[($val->Year) - 2014] = $val->TotalSales;
        }

        $annualSalesGrowthData = [];
        for ($i = 0; $i < count($annualSalesData); $i++) {
            $currentYearSales = $annualSalesData[$i];
            if ($i == 0) {
                $previousYearSales = 0;
            } else {
                $previousYearSales =  $annualSalesData[$i - 1];
            }
            if ($previousYearSales == 0) {
                array_push($annualSalesGrowthData, (float) 0);
            } else {
                $currentYearGrowth = (float) ($currentYearSales - $previousYearSales) / $previousYearSales * 100;
                array_push($annualSalesGrowthData, $currentYearGrowth);
            }
        }
        $annualSalesAndGrowth = [$annualSalesData, $annualSalesGrowthData];
        return $annualSalesAndGrowth;
    }
    public function getTotalStateYearly()
    {
        $query = DB::select(DB::raw("SELECT
                            Year AS Year, STATE_NA AS State, SUM(TOTAL_SALES) AS TotalSales
                            FROM outlet_monthly_sales
                            GROUP BY STATE_NA, YEAR
                            ORDER BY YEAR"));

        $Year = [];
        $total_Sales = [];
        $state = [];
        $data = [];
        foreach ($query as $val) {
            array_push($Year, $val->Year);
            array_push($total_Sales, (float)$val->TotalSales);
            array_push($state, $val->State);
        }
        // for ($i = 0; $i < count($Year); $i++) {
        //     $array = [];
        //     if ($Year[$i] == 2015) {
        //         array_push($array, $state[$i], $total_Sales[$i]);
        //         array_push($aYear, $array);
        //     }
        // }
        // dd($aYear);
        array_push($data, $Year, $state, $total_Sales);
        return $data;
    }

    public function getDepartmentTotalSalesYearly()
    {
        $query =  DB::select(DB::raw("SELECT
        Year AS Year, DEP_NA, SUM(TOTAL_SALES) AS CategorySales
        FROM product_monthly_sales
        GROUP BY DEP_NA,Year
        ORDER BY Year,CategorySales DESC"));

        $Year = [];
        $productCategoryNameData = [];
        $productCategorySalesData = [];

        foreach ($query as $val) {
            array_push($Year, $val->Year);
            array_push($productCategoryNameData, $val->DEP_NA);
            array_push($productCategorySalesData, (float)$val->CategorySales);
        }
        $queryData = [$Year, $productCategoryNameData, $productCategorySalesData];
        return $queryData;
    }
    public function getMonthlySales()
    {
        $query =  DB::select(DB::raw("SELECT YEAR AS YEAR, MONTH AS MONTH, Total_Sales AS totalSales
        FROM monthly_sales"));

        $year = [];
        $month = [];
        $totalSales = [];

        foreach ($query as $val) {
            array_push($year, $val->YEAR);
            array_push($month, $val->MONTH);
            array_push($totalSales, $val->totalSales);
        }

        $data = [$year, $month, $totalSales];
        return $data;
    }
    /**
     * Function to hold all related functions to be viewed and returned
     *
     * @param  Request
     * @return view & array[array[],array[],array[],array[]]
     */
    public function outletIndex(Request $request)   //OUTLET SALES PAGE//
    {
        $index = [
            'outletSalesData' => $this->getOutletSales($request),
            'TotalOutletsByStateData' => $this->getTotalOutletsByState(),
            'outletTotalSalesByStateYearlyData' => $this->getTotalOutletsByStateYearly($request),
            'outletTotalSalesByStateMonthlyData' => $this->getTotalSalesByStateMonthly($request)
        ];
        return view('sales/outlets', compact('index'));
    }

    /**
     * Function to get total sales of outlets by year, month & state selected by user.
     *
     * @param  Request
     * @return array[string[],[string[],[string[],[string[],[string[][float[]]
     */

    public function getOutletSales(Request $request)
    {

        $year = $request->query->get('year');
        $month = $request->query->get('month');
        $state = $request->query->get('state');
        if (!isset($year)) {
            $year = '2020';
        }

        if (!isset($month)) {
            if (!isset($state)) {
                DB::statement("SET SQL_MODE=''"); //to avoid (SQLSTATE[42000]: Syntax error or access violation: 1055), instead of changing ('strict' => false) in (config\database.php --> "mysql")


                // By default, it will choose this query because it will auto set the year.
                // Year set, Month not set, State not set
                // This query is to show outlet with highest sales for that year.


                $query = DB::select(DB::raw("SELECT
                            str_no AS outletCode,
                            str_na AS outlet,
                            Outlet_no AS outletNum,
                            Area_No AS areaCode,
                            Area_Na AS area,
                            SUM(outlet_monthly_sales.TOTAL_SALES) AS totalSales
                            FROM outlet_monthly_sales
                            WHERE outlet_monthly_sales.YEAR = {$year}
                            GROUP BY outlet
                            ORDER BY totalSales DESC"));
            } else {
                DB::statement("SET SQL_MODE=''"); //to avoid (SQLSTATE[42000]: Syntax error or access violation: 1055), instead of changing ('strict' => false) in (config\database.php --> "mysql")


                // Year set, Month not set, State set
                // This query is to show outlet with highest sales in certain states for that year.


                $query = DB::select(DB::raw("SELECT
                            str_no AS outletCode,
                            str_na AS outlet,
                            Outlet_no AS outletNum,
                            Area_No AS areaCode,
                            Area_Na AS area,
                            SUM(outlet_monthly_sales.TOTAL_SALES) AS totalSales
                            FROM outlet_monthly_sales
                            WHERE outlet_monthly_sales.YEAR = {$year} AND outlet_monthly_sales.STATE_NA = '{$state}'
                            GROUP BY outlet
                            ORDER BY totalSales DESC"));
            }
        } else {
            if (!isset($state)) {
                DB::statement("SET SQL_MODE=''"); //to avoid (SQLSTATE[42000]: Syntax error or access violation: 1055), instead of changing ('strict' => false) in (config\database.php --> "mysql")


                // Year set, Month set, State not set
                // This query is to show outlet with highest sales in this year and month.


                $query = DB::select(DB::raw("SELECT
                                                str_no AS outletCode,
                                                str_na AS outlet,
                                                Outlet_no AS outletNum,
                                                Area_No AS areaCode,
                                                Area_Na AS area,
                                                SUM(TOTAL_SALES) AS totalSales
                                                FROM outlet_monthly_sales
                                                WHERE outlet_monthly_sales.YEAR = {$year} AND outlet_monthly_sales.MONTH = {$month}
                                                GROUP BY outlet
                                                ORDER BY totalSales DESC"));
            } else {
                DB::statement("SET SQL_MODE=''"); //to avoid (SQLSTATE[42000]: Syntax error or access violation: 1055), instead of changing ('strict' => false) in (config\database.php --> "mysql")


                // Year set, Month set, State set
                // This query is to show outlet with highest sales based on state in this year and month.


                $query = DB::select(DB::raw("SELECT
                            str_no AS outletCode,
                            str_na AS outlet,
                            Outlet_no AS outletNum,
                            Area_No AS areaCode,
                            Area_Na AS area,
                            SUM(outlet_monthly_sales.TOTAL_SALES) AS totalSales
                            FROM outlet_monthly_sales
                            WHERE YEAR = {$year}  AND MONTH = {$month} AND STATE_NA = '{$state}'
                            GROUP BY str_na
                            ORDER BY totalSales DESC"));
            }
        }

        $outletCode = [];
        $outlet = [];
        $outletNum = [];
        $areaCode = [];
        $area = [];
        $totalSales = [];
        $arrayData = [];
        foreach ($query as $val) {
            array_push($outletCode, $val->outletCode);
            array_push($outlet, $val->outlet);
            array_push($outletNum, $val->outletNum);
            array_push($areaCode, $val->areaCode);
            array_push($area, $val->area);
            array_push($totalSales, number_format((float)$val->totalSales, 2, '.', ','));
        }
        array_push($arrayData, $outletCode, $outlet, $outletNum, $areaCode, $area, $totalSales);
        // ddd($arrayData);
        return $arrayData;
    }

    /**
     * Function to get the total sales of states yearly.
     *
     * @param  Request
     * @return array[string[],float[]]
     */
    public function getTotalOutletsByStateYearly(Request $request)
    {
        $state = $request->query->get('state');

        if (!isset($state)) {
            $state = 'Selangor';
        }
        $query = DB::select(DB::raw("SELECT
                            Year AS Year, STATE_NA , SUM(TOTAL_SALES) AS TotalSales
                            FROM outlet_monthly_sales
                            WHERE STATE_NA = '$state'
                            GROUP BY STATE_NA, YEAR
                            ORDER BY YEAR"));

        $Year = [];
        $total_Sales = [];
        $data = [];
        foreach ($query as $val) {
            array_push($Year, $val->Year);
            array_push($total_Sales, (float)$val->TotalSales);
        }
        array_push($data, $Year, $total_Sales);
        return $data;
    }

    /**
     * Function to get the total number of outlets by states.
     *
     * @return array[int[],string[]]
     */
    public function getTotalOutletsByState()
    {
        DB::statement("SET SQL_MODE=''"); //to avoid (SQLSTATE[42000]: Syntax error or access violation: 1055), instead of changing ('strict' => false) in (config\database.php --> "mysql")
        $query = DB::select(DB::raw("SELECT state_no AS States, COUNT(DISTINCT(str_no)) AS TotalOutlets
                                            FROM outlet_monthly_sales
                                            GROUP BY state_no"));

        $TotalOutlets = [];
        $States = [];
        $data = [];

        foreach ($query as $val) {
            array_push($TotalOutlets, $val->TotalOutlets);
            array_push($States, $val->States);
        }
        array_push($data, $TotalOutlets, $States);
        return $data;
    }

    /**
     * Function to get the total sales of states Monthly.
     * @param  Request
     * @return array[string[],int[]]
     */
    public function getTotalSalesByStateMonthly(Request $request)
    {
        $year = $request->query->get('year');
        $states = $request->query->get('states');

        if (!isset($year)) { // By default, all started with 2015

            $year = 2020;
        }
        if (!isset($states)) { // For all years and states (No choose)
            // For selected years

            $query = DB::select(DB::raw("	SELECT
                                                Month AS Month, SUM(TOTAL_SALES) AS TotalSales
                                                FROM outlet_monthly_sales
                                                WHERE YEAR = $year
                                                GROUP BY Month
                                                ORDER BY Month"));
        } else { //For all selected years and selected months
            //For all selected months only

            $query = DB::select(DB::raw("SELECT
                                MONTH AS Month, STATE_NA, SUM(TOTAL_SALES) AS TotalSales
                                FROM outlet_monthly_sales
                                WHERE YEAR = $year AND STATE_NA = '$states'
                                GROUP BY STATE_NA, Month
                                ORDER BY MONTH"));
        }

        $month = [];
        $total_Sales = [];
        $data = [];

        for ($i = 0; $i < 12; $i++) {
            $month[$i] = $i + 1;
            $total_Sales[$i] = 0;
        }
        foreach ($query as $val) {
            $total_Sales[$val->Month - 1] = $val->TotalSales;
        }

        array_push($data, $month, $total_Sales);
        return $data;
    }

    /**
     * Function to hold all related functions to be viewed and returned
     *
     * @param  string  Request
     * @return view & array[array[],array[],array[],array[]]
     */
    public function products(request $request) //PRODUCT SALES PAGE//
    {
        $charts = [
            'productSalesData' => $this->getProductSalesByProductName($request),
            'productCategorySalesData' => $this->getProductSalesByProductCategory($request),
            'allProductCategoryData' => $this->getAllProductCategory(),
            'allProductNameData' => $this->getAllProductName(),
        ];
        return view("sales/products", compact('charts'));
    }

    /**
     * Get all products sales by year, month and category with a limit.
     *
     * @param  string  Request
     * @return array[string[],string[],string[]]
     */
    public function getProductSalesByProductName(request $request)   //for Product Total Sales by Product Name
    {
        $year = $request->query->get('year');
        $month = $request->query->get('month');
        $category = $request->query->get('category');
        $limit = $request->query->get('limit');

        if (!isset($limit)) {
            $limit = 20;
        }
        DB::statement("SET SQL_MODE=''"); //to avoid (SQLSTATE[42000]: Syntax error or access violation: 1055), instead of changing ('strict' => false) in (config\database.php --> "mysql")
        if (!isset($year)) {   //for all years (dropdown unselected)
            if (!isset($month)) {   //for all months
                if (isset($category)) {   //for selected category
                    $query =  DB::select(DB::raw("SELECT
                                GOO_NO, GOO_NA, SUM(QTY) AS QTY, SUM(TOTAL_SALES) AS TOTAL_SALES
                                FROM product_monthly_sales
                                WHERE DEP_NA = '$category'
                                GROUP BY GOO_NO
                                ORDER BY SUM(TOTAL_SALES) DESC
                                LIMIT {$limit}"));
                } else {  //for all categories
                    $query =  DB::select(DB::raw("SELECT
                                GOO_NO, GOO_NA, SUM(QTY) AS QTY, SUM(TOTAL_SALES) AS TOTAL_SALES
                                FROM product_monthly_sales
                                GROUP BY GOO_NO
                                ORDER BY SUM(TOTAL_SALES) DESC
                                LIMIT {$limit}"));
                }
            } else {   //for selected month
                if (isset($category)) {   //for selected category
                    $query =  DB::select(DB::raw("SELECT
                                GOO_NO, GOO_NA, SUM(QTY) AS QTY, SUM(TOTAL_SALES) AS TOTAL_SALES
                                FROM product_monthly_sales
                                WHERE MONTH = {$month} AND DEP_NA = '{$category}'
                                GROUP BY GOO_NO
                                ORDER BY SUM(TOTAL_SALES) DESC
                                LIMIT {$limit}"));
                } else {    //for all categories
                    $query =  DB::select(DB::raw("SELECT
                                GOO_NO, GOO_NA, SUM(QTY) AS QTY, SUM(TOTAL_SALES) AS TOTAL_SALES
                                FROM product_monthly_sales
                                WHERE MONTH = {$month}
                                GROUP BY GOO_NO
                                ORDER BY SUM(TOTAL_SALES) DESC
                                LIMIT {$limit}"));
                }
            }
        } else {   //for selected year (selected from year dropdown)
            if (!isset($month)) {   //for all months
                if (isset($category)) {   //for selected category
                    $query =  DB::select(DB::raw("SELECT
                                GOO_NO, GOO_NA, SUM(QTY) AS QTY, SUM(TOTAL_SALES) AS TOTAL_SALES
                                FROM product_monthly_sales
                                WHERE YEAR = {$year} AND DEP_NA = '$category'
                                GROUP BY GOO_NO
                                ORDER BY SUM(TOTAL_SALES) DESC
                                LIMIT {$limit}"));
                } else {   //for all categories
                    $query =  DB::select(DB::raw("SELECT
                                GOO_NO, GOO_NA, SUM(QTY) AS QTY, SUM(TOTAL_SALES) AS TOTAL_SALES
                                FROM product_monthly_sales
                                WHERE YEAR = {$year}
                                GROUP BY GOO_NO
                                ORDER BY SUM(TOTAL_SALES) DESC
                                LIMIT {$limit}"));
                }
            } else {   //for selected month
                if (isset($category)) { //for selected category
                    $query =  DB::select(DB::raw("SELECT
                                GOO_NO, GOO_NA, SUM(QTY) AS QTY, SUM(TOTAL_SALES) AS TOTAL_SALES
                                FROM product_monthly_sales
                                WHERE YEAR = {$year} AND MONTH = {$month} AND DEP_NA = '{$category}'
                                GROUP BY GOO_NO
                                ORDER BY SUM(TOTAL_SALES) DESC
                                LIMIT {$limit}"));
                } else {   //for all categories
                    $query =  DB::select(DB::raw("SELECT
                                GOO_NO, GOO_NA, SUM(QTY) AS QTY, SUM(TOTAL_SALES) AS TOTAL_SALES
                                FROM product_monthly_sales
                                WHERE YEAR = {$year} AND MONTH = {$month}
                                GROUP BY GOO_No
                                ORDER BY SUM(TOTAL_SALES) DESC
                                LIMIT {$limit}"));
                }
            }
        }
        $productNoData = [];
        $productNameData = [];
        $productSalesQuantityData = [];
        $productSalesData = [];
        $productSalesPercentageData = [];
        $salesTotal = 0;

        foreach ($query as $val) {
            array_push($productNoData, $val->GOO_NO);
            array_push($productNameData, $val->GOO_NA);
            array_push($productSalesQuantityData, number_format(($val->QTY), 0, '.', ','));
            array_push($productSalesData, $val->TOTAL_SALES);
            $salesTotal += $val->TOTAL_SALES;
        }
        foreach ($productSalesData as $val) {
            array_push($productSalesPercentageData, number_format((float)($val / $salesTotal * 100), 2, '.', ''));
        }

        $formattedProductSalesData = [];
        foreach ($productSalesData as $val) {
            array_push($formattedProductSalesData, number_format($val, 2, '.', ','));
        }

        $queryData = [$productNoData, $productNameData, $productSalesQuantityData, $formattedProductSalesData, $productSalesPercentageData];
        return $queryData;
    }

    /**
     * Get all product categories sales by year and month with a limits.
     *
     * @param  string  Request
     * @return array[string[],string[],float[]]
     */
    public function getProductSalesByProductCategory(request $request)   //for Product Total Sales by Product Category table
    {
        $year = $request->query->get('year');
        $month = $request->query->get('month');
        $limit = $request->query->get('limit');

        if (!isset($limit)) {
            $limit = 5;
        }

        if (!isset($year)) {   //for all years (dropdown unselected)
            if (!isset($month)) {   //select for all months
                $query =  DB::select(DB::raw("SELECT
                            DEP_NA, SUM(TOTAL_SALES) AS CategorySales
                            FROM product_monthly_sales
                            GROUP BY DEP_NA
                            ORDER BY CategorySales DESC
                            LIMIT {$limit}"));
            } else {  //select for selected month
                $query =  DB::select(DB::raw("SELECT
                            DEP_NA, SUM(TOTAL_SALES) AS CategorySales
                            FROM product_monthly_sales
                            WHERE MONTH = {$month}
                            GROUP BY DEP_NA
                            ORDER BY CategorySales DESC
                            LIMIT {$limit}"));
            }
        } else {   //for selected year (selected from year dropdown)
            if (!isset($month)) {   //select for all months
                $query =  DB::select(DB::raw("SELECT
                            DEP_NA, SUM(TOTAL_SALES) AS CategorySales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year}
                            GROUP BY DEP_NA
                            ORDER BY CategorySales DESC
                            LIMIT {$limit}"));
            } else {  //select for selected month
                $query =  DB::select(DB::raw("SELECT
                            DEP_NA, SUM(TOTAL_SALES) AS CategorySales
                            FROM product_monthly_sales
                            WHERE YEAR = {$year} AND MONTH = {$month}
                            GROUP BY DEP_NA
                            ORDER BY CategorySales DESC
                            LIMIT {$limit}"));
            }
        }

        $productCategoryNameData = [];
        $productCategorySalesData = [];
        $productCategorySalesPercentageData = [];
        $salesTotal = 0;
        foreach ($query as $val) {
            array_push($productCategoryNameData, $val->DEP_NA);

            array_push($productCategorySalesData, $val->CategorySales);
            $salesTotal += $val->CategorySales;
        }

        foreach ($productCategorySalesData as $val) {
            array_push($productCategorySalesPercentageData, number_format((float)($val / $salesTotal * 100), 2, '.', ''));
        }

        $formattedProductCategorySalesData = [];    //change the sales format to ex; like '1,234,567.89'
        foreach ($productCategorySalesData as $val) {
            array_push($formattedProductCategorySalesData, number_format((float)$val, 2, '.', ','));
        }
        $queryData = [$productCategoryNameData, $formattedProductCategorySalesData, $productCategorySalesPercentageData];
        return $queryData;
    }

    /**
     * Get the list of all product categories.
     *
     * @return string[]
     */
    public function getAllProductCategory()   //for category dropdown
    {
        $query =  DB::select(DB::raw("SELECT DISTINCT DEP_NA AS Category FROM products ORDER BY Category"));
        $allCategoryData = [];
        foreach ($query as $val) {
            array_push($allCategoryData, $val->Category);
        }
        return $allCategoryData;
    }

    /**
     * Get the list of all product names, product No and supplier names.
     *
     * @return array[int[],float[]]
     */
    public function getAllProductName()   //for 'All Products List' table
    {
        $query =  DB::select(DB::raw("SELECT DISTINCT GOO_NO AS ProductNo, GOO_NA AS ProductName,SUP_NO AS SupplierNo, SUP_NA AS SupplierName
                                    FROM product_monthly_sales
                                    ORDER BY GOO_NO"));
        $ProductNoData = [];
        $ProductNameData = [];
        $SupplierNoData = [];
        $SupplierNameData = [];
        foreach ($query as $val) {
            array_push($ProductNoData, $val->ProductNo);
            array_push($ProductNameData, $val->ProductName);
            array_push($SupplierNoData, $val->SupplierNo);
            array_push($SupplierNameData, $val->SupplierName);
        }
        $queryData = [$ProductNoData, $ProductNameData, $SupplierNoData, $SupplierNameData];
        return $queryData;
    }

    /**
     * Get product annual sales selected by user.
     *
     * @param  string  Request
     * @return array[int[],float[]]
     */
    public function getProductAnnualSales(Request $request)   //for Product Annual Sales chart
    {
        $productNo = $request->query->get('productNo');
        if (!isset($productNo)) {
            $productNo = 001002;   //testing
        }

        $query =  DB::select(DB::raw("SELECT YEAR AS Year, TOTAL_SALES AS TotalSales
                                    FROM product_annual_sales
                                    WHERE GOO_NO = '{$productNo}'"));

        $yearData = [];
        $productAnnualSalesData = [];

        for ($i = 2014; $i <= (int)date("Y"); $i++) {
            array_push($yearData, $i);
            array_push($productAnnualSalesData, 0);
        }
        foreach ($query as $val) {
            for ($i = 0; $i < count($yearData); $i++) {
                if ($val->Year == $yearData[$i]) {
                    $productAnnualSalesData[$i] = $val->TotalSales;
                }
            }
        }
        $queryData = [$yearData, $productAnnualSalesData];
        return $queryData;
    }

    /**
     * Get product monthly sales selected by user.
     *
     * @param  string  Request
     * @return array[int[],float[]]
     */
    public function getProductMonthlySales(Request $request)   //for Product Monthly Sales chart
    {
        $productNo = $request->query->get('productNo');
        $selectedYear = $request->query->get('year');

        if (!isset($selectedYear)) {   //yeardropdown1 not selected
            $selectedYear = 2020;
        }
        $query =  DB::select(DB::raw("SELECT MONTH AS Month, TOTAL_SALES AS TotalSales
                                        FROM product_monthly_sales
                                        WHERE GOO_NO = '$productNo' AND YEAR = $selectedYear"));
        $monthData = [];
        $productMonthlySalesData = [];
        for ($i = 0; $i < 12; $i++) {
            $monthData[$i] = $i + 1;
            $productMonthlySalesData[$i] = 0;
        }
        foreach ($query as $val) {
            $productMonthlySalesData[$val->Month - 1] = $val->TotalSales;
        }
        $queryData = [$monthData, $productMonthlySalesData];
        return $queryData;
    }

    /**
     * Get product quarterly sales selected by user.
     *
     * @param  string  Request
     * @return array[int[],float[]]
     */
    public function getProductQuarterlySales(Request $request)   //for Product Quarterly Sales chart
    {
        $productNo = $request->query->get('productNo');
        $selectedYear = $request->query->get('year');

        if (!isset($selectedYear)) {   //yeardropdown1 not selected
            $selectedYear = 2020;
        }

        $query =  DB::select(DB::raw("SELECT MONTH AS Month, TOTAL_SALES AS TotalSales
                                        FROM product_monthly_sales
                                        WHERE GOO_NO = '$productNo' AND YEAR = $selectedYear"));

        $quarterData = [1, 2, 3, 4];
        $productMonthlySalesData = [];
        $productQuarterlySalesData = [];

        for ($i = 0; $i < 12; $i++) {
            $productMonthlySalesData[$i] = 0;
        }
        foreach ($query as $val) {
            $productMonthlySalesData[$val->Month - 1] = $val->TotalSales;
        }

        $k = 0;
        for ($i = 0; $i < 4; $i++) {
            $productQuarterlySalesData[$i] = 0;
            for ($j = $k; $j < ($i + 1) * 3; $j++) {
                $productQuarterlySalesData[$i] += $productMonthlySalesData[$j];
            }
            $k += 3;
        }
        $queryData = [$quarterData, $productQuarterlySalesData];
        return $queryData;
    }
}
