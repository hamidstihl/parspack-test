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
            $command="zip -r ".Storage::disk('parspack')->path($username)."/".date('Y-m-d').".zip ".Storage::disk('parspack')->path($username);
            exec($command, $process_result,$status);
            //مقدار نتیجه دستور به صورت آرایه ای از خطوط خروجی است
            //تبدیل آریه به رشته برای نمایش
            $result_string="";
            foreach ($process_result as $line)
            {
                //حذف فضای خالی
                $line=trim($line);
                //اگر چیزی در این خط نوشته شده بود
                if($line)
                {
                    $result_string.=$line."\n";
                }
            }
            //عملیات ناموفق
            if($status)
            {
                $response = [
                    'success' => false,
                    'data' => $result_string,
                    'data2' => $command,
                    'message' => 'ایجاد فایل zip با خطا مواجه شد',
                ];
            }
            else
            {
                $response = [
                    'success' => true,
                    'data' => $result_string,
                    'message' => 'فایل zip ایجاد شد',
                ];
            }
        }
        return $response;
    }
}
