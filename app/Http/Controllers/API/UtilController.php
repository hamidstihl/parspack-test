<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UtilController extends Controller
{
    public function Get_server_process()
    {
        exec('ps aux -ww', $process_status);
        return $process_status;
    }

    public function Zip_directory($username)
    {
        if(!Storage::disk('parspack')->exists($username)) {
            $response=[
                'success' => false,
                'data' => '',
                'message' => 'پوشه ای با این نام وجود ندارد',
            ];
        }
        else
        {
            $command="zip -r ".Storage::disk('parspack')->get($username)."/".date('Y-m-d').".zip ".Storage::disk('parspack')->get($username);
            exec($command, $process_result);
            $response = [
                'success' => true,
                'data' => $process_result,
                'message' => 'فایل zip ایجاد شد',
            ];
        }
        return $response;
    }
}
