<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('予約画面') }}
        </h2>
    </x-slot>
    <div>
        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf

            <!-- {{-- 日付 × 時間表 --}} -->
            <div>
                <table border="1">
                    <tr>
                        <th>日付</th>
                        @foreach ($timeSlots as $time)
                            <th>{{ $time }}</th>
                        @endforeach
                    </tr>
                    @foreach ($dates as $d)
                        <tr>
                            <td>{{ $d['date']->format('m/d') }}
                                @if($d['isHoliday'])
                                    <span style="color:red">祝日</span>
                                @endif
                            </td>
                            @foreach ($timeSlots as $time)
                                @php
                                    $datetime = $d['date']->format('Y-m-d') . ' ' . $time;
                                @endphp
                                <td>
                                    <input type="radio" 
                                        name="reserved_at" 
                                        value="{{ $datetime }}">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>

            <!-- {{-- 使用目的 --}} -->
            <div>
                <label for="purpose">使用目的:</label><br>
                <textarea name="purpose" id="purpose" rows="3" required></textarea>
            </div>
            <div>
                <button type="submit">予約完了</button>
            </div>
        </form>
    </div>
</x-app-layout>

