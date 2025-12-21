<?php

namespace App\Http\Controllers;
use App\Models\Person;
use Illuminate\View\View;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index(): View
    {
        // Isso fará com que a view 'resources/views/registrations/church.blade.php' seja carregada

        $activeMembers = Person::where('active', '1')->count();
        $inactiveMembers = Person::where('active', '0')->count();
        $totalMembers = Person::all()->count();


        return view('dashboard', ['activeMembers' => $activeMembers, 'inactiveMembers' => $inactiveMembers, 'totalMembers' => $totalMembers]);
    }

}
