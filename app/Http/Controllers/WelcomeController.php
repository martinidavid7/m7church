<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Person;
use App\Models\Ministry;
use App\Models\Service;
use App\Models\Visitor;

class WelcomeController extends Controller
{
    public function index(): View
    {

        $totalMembers = Person::count();
        $totalVisitors = Visitor::count();
        $totalPeopleServed = $totalMembers + $totalVisitors;
        $ministries = Ministry::count();
        $totalServices = Service::count();

        return view('welcome', [
            'totalPeopleServed' => $totalPeopleServed,
            'ministries' => $ministries,
            'totalServices' => $totalServices,
        ]);

    }
}
