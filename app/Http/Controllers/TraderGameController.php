<?php

namespace App\Http\Controllers;

use App\Models\TraderGame;
use Illuminate\Http\Request;

class TraderGameController extends Controller
{


    public function show(int $id)
    {

        $details = TraderGame::where('dashboard_user', $id)->get(['dashboard_user AS userId', 'trader_games.*']);


        if (count($details) == 0) $details['data'] = false;


        return response()->json($details);
    }
    public function showDate(int $id, string $date, Request $request)
    {

        $unix = strtotime($date);
        $date = date('Y-m-d', $unix);

        $create = TraderGame::firstorCreate(
            [
                'dashboard_user' => $id,
                'date' => $date,
                'dashboard_email' => isset($request->email) ? $request->email : null
            ]
        );


        $details = TraderGame::where(
            [
                'dashboard_user' => $id,
                'date' => $date,

            ]
        )->first(['dashboard_user AS userId', 'trader_games.*']);

        if (!empty($details)) $details['data'] = true;
        $return = empty($details) ? ['data' => false] : $details;

        return response()->json($return);
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'userId' => 'required',
            'date' => 'required',
            'pnl' => 'sometimes',
            'results' => 'sometimes',
           
        ]);

        $date = strtotime($validatedData['date']);
        $formattedDate = date('Y-m-d', $date);

        $gameStats = TraderGame::firstorCreate(
            [
                'dashboard_user' => $validatedData['userId'],
                'date' => $formattedDate,
              
            ]
        );
        if (isset($validatedData['results']['mental'])) {

            $gameStats->mental_score = $validatedData['results']['mental'];
        }
        if (isset($validatedData['results']['tactical'])) {

            $gameStats->tactical_score = $validatedData['results']['tactical'];
        }
        if (isset($validatedData['pnl'])) {

            $gameStats->pnl = $validatedData['pnl'];
        }

        $gameStats->save();


        return response()->json([
            'status' => 'success',
            'message' => 'game stats updated',
            'updated_stats' => $gameStats,
        ]);
    }
}
