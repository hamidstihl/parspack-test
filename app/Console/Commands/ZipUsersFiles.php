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
            echo $response['data']."\n".$response['message']."\n";
            return $response['success'];
        }
        //برای تمام کاربران فایل زیپ را ایجاد میکنیم
        else
        {
            $usernames=User::query()->pluck('username')->toArray();
            foreach ($usernames as $username)
            {
                $response = $this->controller->Zip_directory($username);
            }
            echo "فایل های zip ایجاد شد"."\n";
            return 0;
        }
    }
}
