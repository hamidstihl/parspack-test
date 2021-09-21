<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilController extends Controller
{
    public function Get_server_process()
    {
        exec('ps aux -ww', $process_status);

        // search $needle in process status
        return $process_status;
        /*$result = array_filter($process_status, function($var) {
            return strpos($var, $needle);
        });*/
    }
}
