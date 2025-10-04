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
            'reserved_at' => 'required|array',
            'reserved_at.*' => 'date_format:Y-m-d H:i',
            'purpose' => 'required|string|max:255',
        ]);


        $user = auth()->user();

        // 件数チェック（履歴を含めない）
        $upcomingReservations = $user->reservations()->upcoming()->count();
        $maxReservations = 5;

        if ($upcomingReservations >= $maxReservations) {
            return redirect()->back()
                ->withErrors([
                    'reserved_at' => '予約可能件数の上限に達しました。'
                ]);
        }

        try {
            $reservations = [];
            foreach ($request->reserved_at as $datetime) {
                $reservations[] = Reservation::create([
                    'user_id' => auth()->id(),
                    'reserved_at' => $datetime,
                    'purpose' => $request->purpose,
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] === 1062) {
                // 同じユーザー・日時の重複予約エラー
                return back()->withErrors([
                    'reserved_at' => 'この時間帯はすでに予約済みです。',
                ]);
            }
            throw $e;
        }

        return redirect()
            ->route('reservations.complete')
            ->with([
                'completed' => true,
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
        if (!session('completed')) {
            // 直アクセス防止 → 予約一覧にリダイレクト
            return redirect()->route('reservations.create');
        }

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
}
