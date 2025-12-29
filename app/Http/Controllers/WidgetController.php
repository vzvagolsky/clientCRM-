<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WidgetController extends Controller
{
    public function show(): View
    {
        return view('widget.widget');
    }
}