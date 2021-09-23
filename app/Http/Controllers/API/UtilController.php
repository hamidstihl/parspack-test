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
            $command1="cd ".Storage::disk('parspack')->path('');
            $command2="zip -r ".$username."/".date('Y-m-d').".zip ".$username.'/';
            exec($command1);
            exec($command2, $process_result,$status);
            $err="";
            foreach ($process_result as $line)
                $err.=$line."\n";
            //عملیات ناموفق
            if($status)
            {
                $response = [
                    'success' => false,
                    'data' => $process_result,
                    'message' => $command1."\n".$command2."\n".$err,
                ];
            }
            else
            {
                $response = [
                    'success' => true,
                    'data' => Storage::disk('parspack')->path($username)."/".date('Y-m-d').".zip ",
                    'message' => 'فایل zip ایجاد شد',
                ];
            }
        }
        return $response;
    }
}
