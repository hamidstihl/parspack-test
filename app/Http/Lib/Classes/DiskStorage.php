<?php


namespace App\Http\Lib\Classes;


use App\Http\Lib\Interfaces\StorageManagement;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Support\Facades\Storage;

class DiskStorage implements StorageManagement
{protected $diskName="parspack";
    protected $userDirectory;

    public function Set_root_directory($username)
    {
        $this->userDirectory=$username;
    }

    public function Create_directory($name)
    {
        $path=$this->userDirectory.'/'.$name;
        if(Storage::disk($this->diskName)->exists($path)) {
            $response=[
                'success' => false,
                'data' => '',
                'message' => 'پوشه ای با این نام وجود دارد',
            ];
        }
        else
        {
            Storage::disk($this->diskName)->makeDirectory($path);
            $response=[
                'success' => true,
                'data' => '',
                'message' => 'پوشه ایجاد شد',
            ];
        }
        return $response;
    }

    public function Create_file($name)
    {
        $path=$this->userDirectory.'/'.$name;
        if(Storage::disk($this->diskName)->exists($path)) {
            $response=[
                'success' => false,
                'data' => '',
                'message' => 'فایلی با این نام وجود دارد',
            ];
        }
        else
        {
            Storage::disk($this->diskName)->put($path,"parspack\n".$name);
            $response=[
                'success' => true,
                'data' => '',
                'message' => 'فایل ایجاد شد',
            ];
        }
        return $response;
    }

    public function Get_directories()
    {
        $directories=Storage::disk($this->diskName)->allDirectories($this->userDirectory);
        $response=[
            'success' => true,
            'data' => $directories,
            'message' => '',
        ];
        return $response;
    }

    public function Get_files()
    {
        $files=Storage::disk($this->diskName)->allFiles($this->userDirectory);
        $response=[
            'success' => true,
            'data' => $files,
            'message' => '',
        ];
        return $response;
    }

    public function Delete_directories($name)
    {
        $path=$this->userDirectory.'/'.$name;
        if(!Storage::disk($this->diskName)->exists($path)) {
            $response=[
                'success' => false,
                'data' => '',
                'message' => 'پوشه ای با این نام وجود ندارد',
            ];
        }
        else
        {
            $deleted=Storage::disk($this->diskName)->deleteDirectory($path);
            if($deleted)
            {
                $response = [
                    'success' => true,
                    'data' => '',
                    'message' => 'پوشه حذف شد',
                ];
            }
            else
            {
                $response = [
                    'success' => false,
                    'data' => '',
                    'message' => 'مشکلی در فرایند حذف به وجود آمد',
                ];
            }
        }
        return $response;
    }

    public function Delete_files($name)
    {
        $path=$this->userDirectory.'/'.$name;
        if(!Storage::disk($this->diskName)->exists($path)) {
            $response=[
                'success' => false,
                'data' => '',
                'message' => 'فایلی با این نام وجود ندارد',
            ];
        }
        else
        {
            $deleted=Storage::disk($this->diskName)->delete($path);
            if($deleted)
            {
                $response = [
                    'success' => true,
                    'data' => '',
                    'message' => 'فایل حذف شد',
                ];
            }
            else
            {
                $response = [
                    'success' => false,
                    'data' => '',
                    'message' => 'مشکلی در فرایند حذف به وجود آمد',
                ];
            }
        }
        return $response;
    }

}
