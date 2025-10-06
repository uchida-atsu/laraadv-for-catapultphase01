<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約画面　1/2（予約時間選択）
        </h2>
    </x-slot>

    <div>
        <form action="{{ route('reservations.purpose') }}" method="POST" class="p-4">
            @csrf

            <!-- {{-- 日付 × 時間表 --}} -->
            <div>
                <table class="reservation-table">
                    <thead>
                        <tr>
                            <th>時間\日付</th>
                            @foreach ($dates as $day)
                                <th>{{ $day['date']->format('m/d (D)') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($times as $time)
                            <tr>
                                <td>
                                    {{ $time }}
                                </td>
                                @foreach ($dates as $day)
                                    @php
                                        $dateKey = $day['date']->format('Y-m-d');
                                        $isFull = $timeSlots[$dateKey][$time];
                                        $dateTime = "{$dateKey} {$time}";
                                    @endphp
                                    <td>
                                        <label class="block rounded-lg text-center
                                            {{ $isFull ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-white hover:bg-blue-100' }}">
                                            <input type="checkbox"
                                                name="reserved_at[]"
                                                value="{{ $dateTime }}"
                                                class="hidden peer"
                                                {{ $isFull ? 'disabled' : '' }}>
                                            <span class="peer-checked:bg-blue-300 block rounded-md py-2">
                                                {{ $isFull ? '満席' : '空き' }}
                                            </span>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @foreach ($errors->get('reserved_at') as $message)
                    <div class="font-bold text-red-500">{{ $message }}</div>
                @endforeach

            </div>
            <br>
            <!-- 次ページボタン -->
            <div style="text-align: center; margin-bottom: 8px;">
                <button type="submit" class="reservation-button">次へ</button>
            </div>
        </form>
    </div>
</x-app-layout>

