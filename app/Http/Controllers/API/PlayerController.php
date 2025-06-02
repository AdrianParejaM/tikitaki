<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        return Player::with('club')->get();
    }

    public function store(Request $request)
    {
        return Player::create($request->all());
    }

    public function show(Player $player)
    {
        return $player->load('club', 'users');
    }

    public function update(Request $request, Player $player)
    {
        $player->update($request->all());
        return $player;
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return response()->noContent();
    }

    public function signPlayer(Request $request, Player $player)
    {
        $player->users()->attach(auth()->id(), ['date_signing' => now()]);
        return response()->json(['message' => 'Player signed successfully']);
    }
}
