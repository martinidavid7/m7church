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

        $totalMembers = Person::count();
        $totalVisitors = Visitor::count();
        $totalPeopleServed = $totalMembers + $totalVisitors;
        $totalServices = Service::count();

        $canViewMemberStats = auth()->user()->hasAnyRole([
            'Admin', 'Pastor Presidente', 'Pastor Auxiliar', 'Secretaria',
        ]);

        $data = [
            'totalPeopleServed' => $totalPeopleServed,
            'totalServices' => $totalServices,
            'canViewMemberStats' => $canViewMemberStats,
        ];

        if ($canViewMemberStats) {
            $data['inactiveMembers'] = Person::where('active', '0')->count();
            $data['totalMembers'] = $totalMembers;
        }

        return view('dashboard', $data);
    }
}
