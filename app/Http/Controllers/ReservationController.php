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

        $times = collect();
        $start = Carbon::createFromTime(10, 0);
        $end = Carbon::createFromTime(17, 0);
        while ($start->lte($end)) {
            $times->push($start->format('H:i'));
            $start->addHour();
        }

        $max = 3;
        $timeSlots = [];

        foreach ($dates as $day) {
            $dateKey = $day['date']->format('Y-m-d');
            foreach ($times as $time) {
                $dateTime = Carbon::createFromFormat('Y-m-d H:i', "{$dateKey} {$time}");
                $count = \App\Models\Reservation::where('reserved_at', $dateTime)->count();
                $isFull = $count >= $max;

                $timeSlots[$dateKey][$time] = $isFull;
            }
        }


        // // すでに入っている予約データを取得
        // $reservations = \App\Models\Reservation::whereBetween(
        //     'reserved_at',
        //     [Carbon::tomorrow()->startOfDay(), Carbon::today()->addDays(7)->endOfDay()]
        // )->get();

        return view('reservations.create', compact('dates', 'times', 'timeSlots'));
    }

    public function purpose()
    {
        $selectedSlots = session('reserved_at');


        return view('reservations.purpose', compact('selectedSlots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reserved_at' => 'required|array',
            'purpose' => 'required|array',
            'reserved_at.*' => 'date_format:Y-m-d H:i',
            'purpose.*' => 'required|string|max:255',
        ]);


        // reserved_atの配列を渡す
        $reservedAts = $validated['reserved_at'];
        // purposeの配列を渡す
        $purposes = $validated['purpose'];

        
        $reservations = collect();
        foreach ($reservedAts as $i => $reservedAt) {
            $purpose = $purposes[$i] ?? null;

            if ($purpose) {
                $reservation = Reservation::create([
                    'user_id' => auth()->id(),
                    'reserved_at' => $reservedAt,
                    'purpose' => $purpose,
                ]);
                $reservations->push($reservation);
            }
        }
        

        return redirect()
            ->route('reservations.complete')
            ->with([
                'reservations' => $reservations
            ]);
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
    public function complete()
    {
        // if (!session('completed')) {
        //     // 直アクセス防止 → 予約一覧にリダイレクト
        //     return redirect()->route('reservations.create');
        // }
        
        $reservations = session('reservations', []);

        return view('reservations.complete', [
            'reservations' => $reservations,
        ]);
    }
    /**
     * show the form for editting
     */
    public function manage()
    {
        $user = auth()->user();

        $upcomingReservations = $user->reservations()->upcoming()->orderBy('reserved_at')->get();

        return view('reservations.manage', compact('upcomingReservations'));
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reservations,id',
        ]);

        // ログインユーザー本人の予約だけ削除できるように制限
        auth()->user()
            ->reservations()
            ->upcoming() 
            ->whereIn('id', $request->ids)
            ->delete();

        return redirect()->route('mypage.index')
            ->with('success', '選択した予約を削除しました。');
    }

    public function confirmDate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'reserved_at' => 'required|array',
            'reserved_at.*' => 'date_format:Y-m-d H:i',
        ]);

        // 件数上限チェック
        $upcomingReservations = $user->reservations()->upcoming()->count();
        $maxReservations = 5;

        if ($upcomingReservations >= $maxReservations) {
            return redirect()->route('reservations.create')
                ->withErrors([
                    'reserved_at' => 'あなたの予約可能件数の上限に達しました。',
                ]);
        }

        // 重複予約チェック
        foreach ($request->reserved_at as $dateTime) {
            $exists = $user->reservations()->where('reserved_at', $dateTime)->exists();
            if ($exists) {
                return redirect()->route('reservations.create')
                    ->withErrors([
                        'reserved_at' => "{$dateTime} はすでに予約済みです。",
                    ]);
            }
        }

        // 問題なければ session に保存して purpose へ
        session(['reserved_at' => $request->reserved_at]);

        return redirect()->route('reservations.purpose');
    }
}
