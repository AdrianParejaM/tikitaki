<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index()
    {
        return Club::all();
    }

    public function store(Request $request)
    {
        return Club::create($request->all());
    }

    public function show(Club $club)
    {
        return $club;
    }

    public function update(Request $request, Club $club)
    {
        $club->update($request->all());
        return $club;
    }

    public function destroy(Club $club)
    {
        $club->delete();
        return response()->noContent();
    }
}
