<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\UtilController;
use App\Models\User;
use Illuminate\Console\Command;

class ZipUsersFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:zip {username?}';
    protected $controller;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'zip all on one user(s) files and folders';

    /**
     * Create a new command instance.
     *
     * @param UtilController $controller
     */
    public function __construct(UtilController $controller)
    {
        parent::__construct();
        $this->controller=$controller;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //دریافت ورودی نام کاربری (میتوناند وجود نداشته باشد)
        //اگر نام کاربری وجود نداشت برای همه کاربران فایل zip ایجاد میشود
        //اگر نام کاربری وجود داشت فقط برای همین کاربر
        $username = $this->argument('username');
        if($username)
        {
            if(!User::query()->where('username',$username)->exists())
            {
                echo 'کاربر یافت نشد'."\n";
                return 1;
            }
            $response = $this->controller->Zip_directory($username);
            echo $response['message']."\n";
            if($response['success'])
                echo $response['data']."\n";
            return $response['success'];
        }
        //برای تمام کاربران فایل زیپ را ایجاد میکنیم
        else
        {
            $usernames=User::query()->pluck('username')->toArray();
            $success="";
            $success_count=0;
            $failed_count=0;
            foreach ($usernames as $username)
            {
                $response = $this->controller->Zip_directory($username);
                if($response['success'])
                {
                    $success.=$response['data']."\n";
                    $success_count++;
                }
                else
                {
                    $failed_count++;
                }
            }
            echo "ایجاد ".$failed_count." فایل zip با شکست مواجه شد"."\n"."ایجاد ".$success_count.
                " فایل zip با موفقیت انجام شد"."\n".$success."\n";
            return 0;
        }
    }
}
