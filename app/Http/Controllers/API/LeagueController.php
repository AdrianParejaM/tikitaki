<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Models\Player;
use App\Models\LineUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeagueController extends Controller
{
    public function index()
    {
        return League::with('admin', 'users')->get();
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->leagues()->exists()) {
            return response()->json([
                'message' => 'Ya tienes una liga creada',
                'max_leagues' => 1
            ], 422);
        }

        $request->validate([
            'name_league' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // 1. Crear la liga
            $league = League::create([
                'name_league' => $request->name_league,
                'description' => $request->description,
                'user_id' => $user->id,
                'creation_date' => now()
            ]);

            // 2. Asignar usuario como admin de la liga
            $league->users()->attach($user->id, [
                'role' => 'Admin',
                'union_date' => now()
            ]);

            // 3. Asignar jugadores aleatorios al usuario en esta liga
            $players = Player::inRandomOrder()->limit(11)->get();

            if ($players->count() < 11) {
                throw new \Exception('No hay suficientes jugadores disponibles');
            }

            $user->players()->attach($players->pluck('id'), [
                'league_id' => $league->id,
                'date_signing' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Liga creada con 11 jugadores asignados',
                'league' => $league->load('admin', 'users'),
                'assigned_players' => $players
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'available_players' => $players->count() ?? 0
            ], 422);
        }
    }

    public function show(League $league)
    {
        return $league->load(['admin', 'users']);
    }

    public function update(Request $request, League $league)
    {
        $request->validate([
            'name_league' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);

        $league->update($request->only(['name_league', 'description']));
        return $league->fresh()->load('admin', 'users');
    }

    public function destroy(League $league)
    {
        DB::table('user_player')->where('league_id', $league->id)->delete();
        $league->lineups()->delete();
        $league->users()->detach();
        $league->delete();

        return response()->noContent();
    }

    public function join(Request $request, League $league)
    {
        $user = auth()->user();

        if ($league->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Ya perteneces a esta liga'], 400);
        }

        $league->users()->attach($user->id, [
            'role' => 'Player',
            'union_date' => now()
        ]);

        return response()->json([
            'message' => 'Successfully joined the league',
            'league' => $league->fresh()->load('users')
        ]);
    }

    public function getLeagueUsers(League $league)
    {
        $users = $league->users()
            ->orderBy('name', 'ASC')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'users' => $users,
            'total_users' => $users->count()
        ]);
    }

    public function getRanking(League $league)
    {
        // Devuelve simplemente los usuarios ordenados alfabéticamente
        $users = $league->users()
            ->orderBy('name', 'ASC')
            ->get(['id', 'name']);

        return response()->json($users);
    }

    public function infoLiga(League $league)
    {
        $league->load('admin');

        return response()->json([
            'league_name' => $league->name_league,
            'description' => $league->description,
            'admin_name' => $league->admin->name ?? 'Desconocido'
        ]);
    }

    public function myLeagues()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $leagues = League::where('user_id', $user->id)
            ->with(['admin', 'users'])
            ->get();

        return response()->json([
            'success' => true,
            'leagues' => $leagues,
            'total' => $leagues->count()
        ]);
    }

    public function checkUserLeague()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $league = League::where('user_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'hasLeague' => (bool)$league,
            'leagueId' => $league?->id
        ]);
    }

    public function assignPlayers(Request $request, League $league)
    {
        $request->validate([
            'player_ids' => 'required|array|size:11',
            'player_ids.*' => 'exists:players,id'
        ]);

        $user = auth()->user();
        $user->players()->attach($request->player_ids, [
            'league_id' => $league->id,
            'date_signing' => now()
        ]);

        return response()->json([
            'message' => 'Jugadores asignados correctamente',
            'players' => Player::whereIn('id', $request->player_ids)->get()
        ]);
    }
}
