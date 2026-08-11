<?php

namespace App\Http\Controllers;

use App\Models\Ministry;
use App\Models\Service;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Display the public listing of services (reuniões).
     */
    public function services(): View
    {
        $dayOrder = array_flip(Service::DAYS_OF_WEEK);

        $services = Service::with('serviceType')
            ->get()
            ->sortBy([
                fn ($a, $b) => ($dayOrder[$a->day_of_week] ?? 99) <=> ($dayOrder[$b->day_of_week] ?? 99),
                fn ($a, $b) => $a->time <=> $b->time,
            ]);

        return view('public.services', compact('services'));
    }

    /**
     * Display the public listing of ministries.
     */
    public function ministries(): View
    {
        $ministries = Ministry::orderBy('name')->get();

        return view('public.ministries', compact('ministries'));
    }
}
