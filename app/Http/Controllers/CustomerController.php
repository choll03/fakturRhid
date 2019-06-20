<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;


class CustomerController extends Controller
{
    public function get(){
        return Customer::select('id','nama')->get();
    }
}
