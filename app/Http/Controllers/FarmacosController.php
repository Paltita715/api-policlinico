<?php

namespace App\Http\Controllers;

use App\Models\Farmacos;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FarmacosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Farmacos::all();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmaco = DB::table('farmacos')->where('id_farmaco', intval($id))->first();
        return  $farmaco;
    }

    /**
     * Update the specified resource in storage. (don't need it)
     */
    /*public function update(Request $request, string $id)
    {
        //
    }*/

    /**
     * Remove the specified resource from storage. (why would I, I told you I just 
     * wanna fetch stuff)
     */
    /*public function destroy(string $id)
    {
        //
    }*/

    

    /**
     * Store a newly created resource in storage. (don't need it, i'm just gonna fetch
     * stuff from a json)
     */
    /*public function store(Request $request)
    {
        //
    }*/
}