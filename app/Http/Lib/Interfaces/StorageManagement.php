<?php


namespace App\Http\Lib\Interfaces;


interface StorageManagement
{
    public function Create_directory($name);

    public function Create_file($name);

    public function Get_directories();

    public function Get_files();

    public function Delete_directories($name);

    public function Delete_files($name);

    public function Set_root_directory($username);
}
