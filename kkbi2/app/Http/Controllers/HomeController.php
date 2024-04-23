<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{

    function getPreviousMonth()
    {
        $currentMonth = intval(date('m'));
        if ($currentMonth === 1) {
            return 12;
        } else {
            return $currentMonth - 1;
        }
    }

    function getCurrentYear()
    {
        ////---hardcoded---////
        if ($this->getPreviousMonth() == 12) {
            return 2020;
        } else {
            return 2021;
        }
        return 2021;

        ///---shoud be this one---///
        // $currentMonth = intval(date('m'));
        // if ($currentMonth === 1) {
        //     return intval(date('Y')) - 1;
        // } else {
        //     return intval(date('Y'));
        // }
    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $tbl_sales = "sales2";

    /**
     * Show the application dashboard with returned variables.
     *
     * @return array[array[]x8]
     */
    public function index()
    {
        //Dummy data only
        $year = 2020;

        $users = User::count();
        $widget = [
            'users' => $users,
            'monthly_sales' => $this->getPreviousMonthSales(),
            'annual_sales' => $this->getAnnualSales(),
            'monthly_quantity' => $this->getMonthlyQuantity(),
            'annual_quantity' => $this->getAnnualQuantity(),
            'whole_year' => $this->getCurrentYearMonthlySales(),
            'top_department' => $this->getTop5Department($year),
            'top_state' => $this->getTop5State($year),
            'tooltip_monthly_sales' => $this->getToolTip()
            //...
        ];
        return view('home', compact('widget'));
    }

    /**
     * Get current month sales.
     *
     * @return float
     */

    public function getPreviousMonthSales()
    {
        $query = DB::select(DB::raw("SELECT SUM(Total_Sales) as Total_sales
                                        FROM monthly_sales
                                        WHERE YEAR = {$this->getCurrentYear()} AND MONTH = {$this->getPreviousMonth()}"));
        return $query[0]->Total_sales;
    }

    /**
     * Get current year sales.
     *
     * @return float
     */
    public function getAnnualSales()
    {
        $query = DB::select(DB::raw("SELECT SUM(TOTAL_SALES) AS Total_Sales
                                        FROM outlet_monthly_sales
                                        WHERE YEAR = {$this->getCurrentYear()}"));
        return $query[0]->Total_Sales;
    }

    /**
     * Get previous month's number of products sold.
     *
     * @return int
     */
    public function getMonthlyQuantity()
    {
        $query = DB::select(DB::raw("SELECT SUM(QTY) as Quantity FROM product_monthly_sales
                                        WHERE YEAR = {$this->getCurrentYear()}
                                        AND MONTH = {$this->getPreviousMonth()}"));

        return $query[0]->Quantity;
    }

    /**
     * Get current year's number of products sold.
     *
     * @return int
     */
    public function getAnnualQuantity()
    {
        $query = DB::select(DB::raw("SELECT SUM(QTY) AS Quantity FROM product_monthly_sales
                                        WHERE YEAR = {$this->getCurrentYear()}"));
        return $query[0]->Quantity;
    }

    /**
     * Get previous month's sales.
     *
     * @return float[]
     */
    function getCurrentYearMonthlySales()
    {
        $query = DB::select(DB::raw("SELECT MONTH AS Month, SUM(TOTAL_SALES) AS TotalSales
                                        FROM product_monthly_sales
                                        WHERE YEAR = {$this->getCurrentYear()}
                                        -- WHERE YEAR = 2020
                                        GROUP BY MONTH"));

        $values = array_fill(0, 12, 0);
        for ($i = 0; $i < count($query); $i++) {
            $values[$query[$i]->Month - 1] = (float)$query[$i]->TotalSales;
        }
        return $values;
    }

    /**
     * Get top 4 DEP_NA with their sales and product quantity sold.
     *
     * @return array[string[],float[],int[]]
     */
    function getTop5Department($year)   ////TO DO NEXTTTTT////
    {
        $query =  DB::select(DB::raw("SELECT dep_na AS DepartmentName,SUM(total_sales) AS TotalSales,SUM(qty) AS TotalQuantity
                    FROM product_annual_sales
                    WHERE YEAR = '{$year}'
                    GROUP BY DEP_NA
                    ORDER BY TotalSales DESC
                    LIMIT 10"));

        $DepartName = [];
        $TotalSales = [];
        $TotalQuantity = [];
        $Department = [];
        foreach ($query as $val) {

            array_push($DepartName, $val->DepartmentName);
            array_push($TotalSales, (float)$val->TotalSales);
            array_push($TotalQuantity, (int)$val->TotalQuantity);
        }
        array_push($Department, $DepartName, $TotalSales, $TotalQuantity);
        return $Department;
    }

    /**
     * Get top 5 state with their total sales
     *
     * @return array[string[],float[]]
     */
    function getTop5State($year)
    {
        $query =  DB::select(DB::raw("SELECT
            STATE_NA AS States, SUM(TOTAL_SALES) AS TotalSales
            FROM outlet_monthly_sales
            WHERE YEAR = '{$year}'
            GROUP BY States
            ORDER BY TotalSales DESC
            LIMIT 5"));

        $StateName = [];
        $TotalSales = [];

        foreach ($query as $val) {
            $StateName[] = $val->States;
            $TotalSales[] = (float)$val->TotalSales;
        }

        $States = [$StateName, $TotalSales];
        return $States;
    }
    function getTopDepartmentMonthly(Request $request)
    {
        //To get the number of month

        $month = $request->query->get('month');
        $arrayofMonth = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $numberofMonth = 0;
        for ($i = 0; $i < count($arrayofMonth); $i++) {
            if ($arrayofMonth[$i] == $month) {
                $numberofMonth = $i + 1;
                break;
            }
        }

        $query =  DB::select(DB::raw("SELECT dep_na AS DepartmentName,SUM(total_sales) AS TotalSales
        FROM product_monthly_sales
        WHERE MONTH = '{$numberofMonth}' AND YEAR = {$this->getCurrentYear()}
        GROUP BY DEP_NA
        ORDER BY TotalSales DESC
        LIMIT 1"));

        $DepartName = [];
        $TotalSales = [];
        $Department = [];
        foreach ($query as $val) {

            array_push($DepartName, $val->DepartmentName);
            array_push($TotalSales, (float)$val->TotalSales);
        }
        array_push($Department, $DepartName, $TotalSales);
        return $Department;
    }

    function getToolTip()
    {
        //FOR DEPARTMENT SALES
        $departmentQuery =  DB::select(DB::raw("SELECT
                                        MONTH, DEP_NA AS DepartmentName,SUM(total_sales) AS TotalSales
                                        FROM product_monthly_sales
                                        WHERE YEAR = {$this->getCurrentYear()}
                                        GROUP BY MONTH, DepartmentName
                                        ORDER BY MONTH, TotalSales DESC"));

        $month = [];
        $departName = [];
        $totalSales = [];
        $depData = [];

        $department = array_fill(0, 12, []);

        foreach ($departmentQuery as $val) {
            $department[$val->MONTH - 1][] = [$val->DepartmentName, $val->TotalSales];
        }


        //calculate percentage increase
        //Dec previous year query for current year Jan increase percentage
        $previousYearDecSalesQuery =  DB::select(DB::raw("SELECT
                                        DEP_NA AS DepartmentName,SUM(total_sales) AS TotalSales
                                        FROM product_monthly_sales
                                        WHERE YEAR = {$this->getCurrentYear()}-1 AND MONTH = 12
                                        GROUP BY DepartmentName
                                        ORDER BY MONTH, TotalSales DESC"));

        $departNamePreYear = [];
        $monthlySalesPreYear = [];
        foreach ($previousYearDecSalesQuery as $val) {
            array_push($departNamePreYear, $val->DepartmentName);
            array_push($monthlySalesPreYear, $val->TotalSales);
        }

        for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {
            // Create an associative array of department names and sales for the current month
            $currentMonthDepartments = [];
            for ($i = 0; $i < count($department[$currentMonth - 1]); $i++) {
                if (isset($department[$currentMonth - 1][$i])) {
                    $currentMonthDepName = $department[$currentMonth - 1][$i][0];
                    $currentMonthSales = $department[$currentMonth - 1][$i][1];
                    $currentMonthDepartments[$currentMonthDepName] = $currentMonthSales;
                }
            }

            // Create an associative array of department names and sales for the previous month
            $previousMonthDepartments = [];
            if ($currentMonth > 1) {   // get previous month sales if month > 1
                for ($j = 0; $j < count($department[$currentMonth - 2]); $j++) {
                    $previousMonthDepName = $department[$currentMonth - 2][$j][0];
                    $previousMonthSales = $department[$currentMonth - 2][$j][1];
                    $previousMonthDepartments[$previousMonthDepName] = $previousMonthSales;
                }
            } else {   // get previous month sales if month == 1 (FOR JANUARY)
                for ($j = 0; $j < count($departNamePreYear); $j++) {
                    $previousMonthDepName = $departNamePreYear[$j];
                    $previousMonthSales = $monthlySalesPreYear[$j];
                    $previousMonthDepartments[$previousMonthDepName] = $previousMonthSales;
                }
            }

            // Calculate the percentage increase for each department
            $numDepartments = count($department[$currentMonth - 1]);
            for ($i = 0; $i < $numDepartments; $i++) {
                if (isset($department[$currentMonth - 1][$i])) {
                    $currentMonthDepName = $department[$currentMonth - 1][$i][0];
                    $currentMonthSales = $department[$currentMonth - 1][$i][1];

                    $previousMonthSales = $previousMonthDepartments[$currentMonthDepName] ?? 1;
                    $previousMonthSales = $previousMonthSales == 0 ? 1 : $previousMonthSales;
                    $percentIncrease = ($currentMonthSales - $previousMonthSales) / $previousMonthSales * 100;
                    array_push($department[$currentMonth - 1][$i], number_format($percentIncrease, 2));
                }
            }
        }

        //sort the $department[month][value][data], 3rd element of 'data' array in descending order
        function sortArrays($arr)
        {
            // sort the 2nd array in each 1st array based on the descending order of the 3rd elements in the 3rd array using insertion sort
            foreach ($arr as &$firstArray) {
                for ($i = 1; $i < count($firstArray); $i++) {
                    $j = $i - 1;
                    $current = $firstArray[$i];
                    while ($j >= 0 && floatval($firstArray[$j][2]) < floatval($current[2])) {
                        $firstArray[$j + 1] = $firstArray[$j];
                        $j--;
                    }
                    $firstArray[$j + 1] = $current;
                }
            }
            return $arr;
        }
        $department = sortArrays($department);
        $allValue = [];
        array_push($allValue, $department);
        return $allValue;
    }
}
