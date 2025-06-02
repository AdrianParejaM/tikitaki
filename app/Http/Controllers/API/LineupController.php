<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lineup;
use Illuminate\Http\Request;

class LineupController extends Controller
{
    public function index()
    {
        return Lineup::with('user', 'league')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_lineUp' => 'required',
            'description' => 'nullable',
            'league_id' => 'required|exists:leagues,id'
        ]);

        return Lineup::create([
            'name_lineUp' => $request->name_lineUp,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'league_id' => $request->league_id
        ]);
    }

    public function show(Lineup $lineup)
    {
        return $lineup->load('user', 'league');
    }

    public function update(Request $request, Lineup $lineup)
    {
        $lineup->update($request->all());
        return $lineup;
    }

    public function destroy(Lineup $lineup)
    {
        $lineup->delete();
        return response()->noContent();
    }
}
