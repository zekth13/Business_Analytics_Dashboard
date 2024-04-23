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
use Illuminate\Support\Facades\Http;

// Example in Laravel app/Http/Controllers/FlaskController.php

class storeforecastcontroller extends Controller
{
    public function index()
    {
        return view("storeforecast");
    }
    public function monthlystoreforecast(Request $request)
    {
        $store = $request->query->get('store');
        $data_to_send = $store;

        // Make a POST request to Flask endpoint
        $response = Http::post('http://127.0.0.1:5000/monthstoreprediction', [
            'data' => $data_to_send
        ]);
        $xgb_data = $response[0];
        $poly_data = $response[1];
        $original_data = $response[2];
        $eval_mape = $response[3];
        $eval_rmse = $response[4];
        return [$xgb_data, $poly_data, $original_data,$eval_mape,$eval_rmse];
    }
    public function yearlystoreforecast(Request $request)
    {
        $store = $request->query->get('store');
        $data_to_send = $store;

        // Make a POST request to Flask endpoint
        $response = Http::post('http://127.0.0.1:5000/yearstoreprediction', [
            'data' => $data_to_send
        ]);
        $xgb_data = $response[0];
        $poly_data = $response[1];
        $original_data = $response[2];
        $eval_mape = $response[3];
        $eval_rmse = $response[4];
        return [$xgb_data, $poly_data, $original_data,$eval_mape,$eval_rmse];
    }
}
