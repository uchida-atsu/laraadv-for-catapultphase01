<x-app-layout>
    <div>
        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf

            <!-- {{-- 日付 × 時間表 --}} -->
            <div>
                <table class="reservation-table">
                    <thead>
                        <tr>
                            <th>日付</th>
                            @foreach ($timeSlots as $time)
                                <th>{{ $time }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dates as $d)
                            <tr>
                                <td>
                                    {{ $d['date']->format('m/d') }}
                                    @if($d['isHoliday'])
                                        <span class="holiday">祝日</span>
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
                    </tbody>
                </table>

                @error('reserved_at')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
            <br>
            <!-- {{-- 使用目的 --}} -->
            <div class="form-group">
                <label for="purpose">使用目的</label>
                <input name="purpose" id="purpose" required class="form-input">
            </div>
            <br>
            <!-- 完了ボタン -->
            <div style="text-align: center; margin-bottom: 8px;">
                <button type="submit" class="reservation-button">予約完了</button>
            </div>
        </form>
    </div>
</x-app-layout>

