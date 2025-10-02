<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Yasumi\Yasumi;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reservations = Reservation::where('user_id', auth()->id())
        ->orderBy('reserved_at', 'asc')
        ->get();

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $year = Carbon::now()->year;
        $holidays = Yasumi::create('Japan', $year, 'ja_JP');

        // 明日から7日間の日付
        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::tomorrow()->addDays($i);
            $dates->push([
                'date' => $date,
                'isHoliday' => $holidays->isHoliday($date),
            ]);
        }

        // 10時から17時まで一時間で区切る
        $timeSlots = [];
        $start = Carbon::createFromTime(10, 0); // 10:00
        $end = Carbon::createFromTime(17, 0);   // 17:00

        while ($start->lte($end)) {
            $timeSlots[] = $start->format('H:i'); // "10:00"
            $start->addHour();
        }

        // すでに入っている予約データを取得
        $reservations = \App\Models\Reservation::whereBetween(
            'reserved_at',
            [Carbon::tomorrow()->startOfDay(), Carbon::today()->addDays(7)->endOfDay()]
        )->get();

        return view('reservations.create', compact('dates', 'timeSlots', 'reservations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reserved_at' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        try {
            $reservation = Reservation::create([
                'user_id' => auth()->id(),
                'reserved_at' => $request->reserved_at,
                'purpose' => $request->purpose,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                // 同じユーザー・日時の重複予約エラー
                return back()->withErrors([
                    'reserved_at' => 'この時間帯はすでに予約済みです。',
                ]);
            }
            throw $e;
        }

        // return redirect()->route('reservations.complete')->with('success', '予約が完了しました');
        return redirect()->route('reservations.complete', ['reservation' => $reservation->id])->with('completed', true);
    }



    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    /**
     * show the result
     */
    public function complete(Reservation $reservation)
    {
        if (!session('completed')) {
            // 直アクセス防止 → 予約一覧にリダイレクト
            return redirect()->route('reservations.create');
        }

        return view('reservations.complete');
    }

    public function manage()
    {
        $user = auth()->user();

        $upcomingReservations = $user->reservations()->upcoming()->orderBy('reserved_at')->get();

        return view('reservations.manage', compact('upcomingReservations'));
    }
}
