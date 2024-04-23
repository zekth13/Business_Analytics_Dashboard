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

class inventoryController extends Controller
{
    public function index(Request $request)
    {
        $index = [
            'getTable' => $this->getTable($request)
        ];
        return view("inventory/inventory", compact('index'));
    }


     /**
     * Function to hold all related functions to be viewed and returned
     *
     * @param  string  Request
     * @return view & array[array[]]
     */
    public function getChart(Request $request)
    {
        $goo_no = $request->query->get('goo_no');

        $query = DB::select(DB::raw("SELECT
                                    YEAR AS Years, SUM(TOTAL_SALES) AS TotalSales
                                    FROM product_annual_sales
                                    WHERE GOO_NO = '$goo_no'
                                    GROUP BY Years"));

        $TotalSales = [];
        foreach ($query as $val) {
            array_push($TotalSales, $val->TotalSales);
        }
        return $TotalSales;
    }


     /**
     * Function to hold all related functions to be viewed and returned
     *
     * @param  string  Request
     * @return view & array[array[],array[],array[]]
     */
    public function getTable(Request $request)
    {
        $cla_na = $request->query->get('cla_na');
        if (!isset($cla_na)) {  //show all
            $query =  DB::select(DB::raw("SELECT products.GOO_NO as ProductNo, products.GOO_NA as ProductName, products.status_desc AS ProductStatus
                        FROM products
                        ORDER BY ProductStatus DESC, ProductName"));
        } else {    //show selected
            $query =  DB::select(DB::raw("SELECT products.GOO_NO as ProductNo, products.GOO_NA as ProductName, products.status_desc AS ProductStatus
                        FROM products
                        WHERE cla_na = '{$cla_na}'
                        ORDER BY ProductStatus DESC, ProductName"));
        }
        $ProductNo = [];
        $ProductName = [];
        $ProductStatus = [];
        $ProductsArray = [];

        foreach ($query as $val) {
            array_push($ProductNo, $val->ProductNo);
            array_push($ProductName, $val->ProductName);
            if ($val->ProductStatus == "Delete") {
                array_push($ProductStatus, "Deleted");
            } else {
                array_push($ProductStatus, $val->ProductStatus);
            }
        }
        array_push($ProductsArray, $ProductNo, $ProductName, $ProductStatus);
        // ddd($ProductsArray);
        return $ProductsArray;
    }


     /**
     * Function to hold all related functions to be viewed and returned
     *
     * @param  string  Request
     * @return string  option
     */
    public function getClass(Request $request)
    {
        $dep_na = $request->query->get('dep_na');

        $query =  DB::select(DB::raw("SELECT cla_na AS class from products
                    WHERE dep_na = '{$dep_na}'
                    GROUP BY cla_na;"));

        $class = [];
        foreach ($query as $val) {
            array_push($class, $val->class);
        }

        $option = '';
        for ($i = 0; $i < count($class); $i++) {
            $option .= '<option value="' . $class[$i] . '">' . $class[$i] . '</option>';
        }
        return $option;
    }
}
