<?php

namespace App\Http\Controllers;

use App\Models\Debt;

class DebtController extends Controller
{
    public function index()
    {
        return Debt::all();
    }

    public function show(Debt $debt)
    {
        return $debt;
    }
}