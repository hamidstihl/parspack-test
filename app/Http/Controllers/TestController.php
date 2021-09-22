<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\TokenAuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    private $AuthController;

    public function __construct(TokenAuthController $authController)
    {
        $this->AuthController=$authController;
    }

    public function RegisterUserTest(int $count)
    {
        $test_users=[];
        $errors=[];
        for($i=0;$i<$count;$i++)
        {
            // ساخت کاربر تستی
            $user=[
                'username' => 'test_'.time().'_'.$i,
                'password' => 'test_test_'.$i,
                'register' => false,
                'login' => false,
                'delete' => false,
            ];
            //تست قسمت مربوط به ثبت نام
            $request=new Request(['username'=>$user['username'],'password'=>$user['password'],'password_confirmation'=>$user['password']]);
            $register=$this->AuthController->Register($request);
            $register=(array)json_decode($register->content());
            $user['register']=$register['success'];
            if(!$register['success'])
            {
                $errors[]=['test number'=>$i,'message'=>$register['message']];
                $test_users[]=$user;
                continue;
            }
            // تست ورود کاربر
            $find_user = User::query()->where('username',$user['username'])->first();
            $login=$find_user && Hash::check($user['password'], $find_user->password);
            $user['login']=$login;
            if(!$login)
            {
                $errors[]=['test number'=>$i,'message'=>'ورود به حساب ناموفق'];
                $test_users[]=$user;
                continue;
            }
            //حذف کاربر تستی
            $delete=$find_user->delete();
            $user['delete']=$delete;
            if(!$delete)
            {
                $errors[]=['test number'=>$i,'message'=>'حذف کاربر ناموفق'];
            }
            $test_users[]=$user;
        }
        return ['errors' => $errors,'test users' => $test_users];
    }

    public function CreateDirectoryTest(int $count) {
        if($count>50)
            return "حداکثر تعداد پوشه های تست 50 میباشد";
        $test_directories=[];
        $errors=[];
        // ساخت کاربر تستی
        $user=User::query()->create([
            'username' => 'test_'.time(),
            'password' => Hash::make('test_test'),
        ]);
        if(!$user)
            return ['ثبت نام کاربر تستی ناموفق بود'];
        //ورود به حساب کاربر تستی تا پوشه ها در دایرکتوری این کاربر ایجاد شود
        Auth::login($user);
        //ایجاد پوشه ها
        for($i=0;$i<$count;$i++)
        {
            // ساخت پوشه تستی
            $directory= 'test_directory_'.time().'_'.$i;
            //تست قسمت مربوط به ایجاد پوشه
            $request = Request::create('/api/directory', 'PUT', ['name'=>$directory]);
            $create = app()->handle($request);
            $create=(array)json_decode($create->getContent());
            if(!$create['success'])
                $errors[]=['test number'=>$i,'message'=>$create['message']];
            $test_directories[]=$directory;
        }
        //دریافت لیست پوشه های این کاربر
        $request = Request::create('/api/directory');
        $userDirectories = app()->handle($request);
        $userDirectories=(array)json_decode($userDirectories->getContent());
        if(!$userDirectories['success'])
        {
            $errors[]=['test number'=>'دریافت لیست پوشه های کاربر ناموفق بود','message'=>$userDirectories['message']];
            return ['errors' => $errors,'test directories' => $test_directories];
        }
        if(count($test_directories)!==count($userDirectories['data']))
            $errors[]=['test number'=>null,'message'=>'تعداد پوشه های ایجاد شده و دریافت شده برابر نیست'];
        //برسی پوشه ها
        for($i=0;$i<count($test_directories);$i++)
        {
            $name=$user->username.'/'.$test_directories[$i];
            if(!in_array($name,$userDirectories['data']))
                $errors[]=['test number'=>$i,'message'=>'پوشه یافت نشد'];
        }
        //حذف کاربر تستی
        $delete=$user->delete();
        if(!$delete)
            $errors[]=['test number'=>null,'message'=>'حذف کاربر تستی ناموفق'];
        $test_users[]=$user;
        return ['errors' => $errors,'test directories' => $test_directories];
    }

    public function CreateFileTest(int $count) {
        if($count>50)
            return "حداکثر تعداد فایلها های تست 50 میباشد";
        $test_files=[];
        $errors=[];
        // ساخت کاربر تستی
        $user=User::query()->create([
            'username' => 'test_'.time(),
            'password' => Hash::make('test_test'),
        ]);
        if(!$user)
            return ['ثبت نام کاربر تستی ناموفق بود'];
        //ورود به حساب کاربر تستی تا فایل ها در دایرکتوری این کاربر ایجاد شود
        Auth::login($user);
        //ایجاد فایل ها
        for($i=0;$i<$count;$i++)
        {
            // ساخت فایل تستی
            $file= 'test_file_'.time().'_'.$i;
            //تست قسمت مربوط به ایجاد فایل
            $request = Request::create('/api/file', 'PUT', ['name'=>$file]);
            $create = app()->handle($request);
            $create=(array)json_decode($create->getContent());
            if(!$create['success'])
                $errors[]=['test number'=>$i,'message'=>$create['message']];
            $test_files[]=$file;
        }
        //دریافت لیست فایل های این کاربر
        $request = Request::create('/api/file');
        $userFiles = app()->handle($request);
        $userFiles=(array)json_decode($userFiles->getContent());
        if(!$userFiles['success'])
        {
            $errors[]=['test number'=>'دریافت لیست فایل های کاربر ناموفق بود','message'=>$userFiles['message']];
            return ['errors' => $errors,'test files' => $test_files];
        }
        if(count($test_files)!==count($userFiles['data']))
            $errors[]=['test number'=>null,'message'=>'تعداد فایل های ایجاد شده و دریافت شده برابر نیست'];
        //برسی فایل ها
        for($i=0;$i<count($test_files);$i++)
        {
            $name=$user->username.'/'.$test_files[$i];
            if(!in_array($name,$userFiles['data']))
                $errors[]=['test number'=>$i,'message'=>'فایل یافت نشد'];
        }
        //حذف کاربر تستی
        $delete=$user->delete();
        if(!$delete)
            $errors[]=['test number'=>null,'message'=>'حذف کاربر تستی ناموفق'];
        $test_users[]=$user;
        return ['errors' => $errors,'test files' => $test_files];
    }
}
