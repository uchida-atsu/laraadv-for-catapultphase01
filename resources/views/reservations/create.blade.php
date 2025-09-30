<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('予約画面') }}
        </h2>
    </x-slot>
    <div>
        <table class="table-auto border-collapse border">
            <thead>
                <tr>
                    <th></th>
                    @foreach ($dates as $date)
                        <th class="border px-4 py-2">{{ $date['date']->format('m/d') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($timeSlots as $slot)
                    <tr>
                        <td class="border px-4 py-2">{{ $slot }}</td>
                        @foreach ($dates as $date)
                            @php
                                $reservedAt = $date['date']->copy()->setTimeFromTimeString($slot);
                                $count = $reservations->where('reserved_at', $reservedAt)->count();
                                $max = 3;
                                $isFull = $count >= $max;
                            @endphp
                            <td class="border px-4 py-2 text-center">
                                @if ($isFull)
                                    <span class="text-red-500">満</span>
                                @else
                                    <button type="button"
                                        wire:click="selectSlot('{{ $reservedAt }}')"
                                        class="px-2 py-1 bg-blue-500 text-white rounded">
                                        ○
                                    </button>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

