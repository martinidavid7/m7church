<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Person;

class WelcomeController extends Controller
{
    public function index(): View
    {

         $activeMembers = Person::where('active', '1')->count();
        return view('welcome', ['activeMembers' => $activeMembers]);

    }
}
