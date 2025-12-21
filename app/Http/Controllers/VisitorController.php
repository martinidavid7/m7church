<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\City;
use App\Models\UF;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $visitors = Visitor::paginate(15);
        return view('registrations.visitor_list', ['visitors' => $visitors]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $cities = City::all();
        $uf = UF::all();
        return view('registrations.visitor_create', ['uf' => $uf ,'cities' => $cities]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Visitor $visitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitor $visitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visitor $visitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitor $visitor)
    {
        //
    }
}
