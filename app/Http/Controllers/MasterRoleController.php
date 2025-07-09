<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MasterRoleController extends Controller
{
    public function index()
    {
        return view('master-role.index');
    }

    public function add()
    {
        return view('master-role.add');
    }

    public function edit()
    {
        return view('master-role.edit');
    }
}
