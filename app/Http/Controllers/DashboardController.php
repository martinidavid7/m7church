<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Service;
use App\Models\Visitor;

use Illuminate\View\View;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Isso fará com que a view 'resources/views/registrations/church.blade.php' seja carregada

        $inactiveMembers = Person::where('active', '0')->count();
        $totalMembers = Person::all()->count();
        $totalVisitors = Visitor::count();
        $totalPeopleServed = $totalMembers + $totalVisitors;
        $totalServices = Service::count();


        return view('dashboard', [
            'inactiveMembers' => $inactiveMembers,
            'totalMembers' => $totalMembers,
            'totalPeopleServed' => $totalPeopleServed,
            'totalServices' => $totalServices,

        ]);
    }
}
