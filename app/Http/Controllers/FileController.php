<?php

namespace App\Http\Controllers;

use App\Http\Lib\Interfaces\StorageManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    private $storageObg;

    public function __construct(StorageManagement $storageObg)
    {
        $this->middleware('auth:sanctum');
        $this->storageObg=$storageObg;
        if(Auth::check())
        {
            $this->storageObg->Set_root_directory(Auth::user()->username);
        }
        else {
            $this->middleware(function ($request, $next) {
                $this->storageObg->Set_root_directory(Auth::user()->username);
                return $next($request);
            });
        }
    }

    public function Create_directory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/(^[a-zA-Z_\d]+$)/u|min:3',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'دادهای ورودی نامعتبر است'
            ];
            return $this->Format_response($response);
        }
        return $this->Format_response($this->storageObg->Create_directory($request['name']));
    }

    public function Create_file(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/(^[a-zA-Z_\d]+$)/u|min:3',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'دادهای ورودی نامعتبر است'
            ];
            return $this->Format_response($response);
        }
        return $this->Format_response($this->storageObg->Create_file($request['name']));
    }

    public function Get_directories()
    {
        return $this->Format_response($this->storageObg->Get_directories());
    }

    public function Get_files()
    {
        return $this->Format_response($this->storageObg->Get_files());
    }

    public function Delete_directories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/(^[a-zA-Z_\d]+$)/u|min:3',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'دادهای ورودی نامعتبر است'
            ];
            return $this->Format_response($response);
        }
        return $this->Format_response($this->storageObg->Delete_directories($request['name']));
    }

    public function Delete_files(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/(^[a-zA-Z_\d]+$)/u|min:3',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'دادهای ورودی نامعتبر است'
            ];
            return $this->Format_response($response);
        }
        return $this->Format_response($this->storageObg->Delete_files($request['name']));
    }

    private function Format_response($response){
        if($response['success'])
            return response($response);
        return response($response,400);
    }
}
