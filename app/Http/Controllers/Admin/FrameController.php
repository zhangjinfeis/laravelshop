<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrameController extends Controller
{
    //

    public function index(Request $request){

        return view('admin.frame.index');
    }
}
