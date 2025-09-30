<x-app-layout>
    @extends('layouts.app')

    @section('content')
    <div class="max-w-md mx-auto">
        <h2 class="text-xl font-bold mb-4">予約の編集</h2>

        <form method="POST" action="{{ route('reservations.update', $reservation) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1">日時</label>
                <input type="datetime-local" 
                    name="reserved_at" 
                    value="{{ old('reserved_at', $reservation->reserved_at->format('Y-m-d\TH:i')) }}" 
                    class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block mb-1">目的</label>
                <input type="text" 
                    name="purpose" 
                    value="{{ old('purpose', $reservation->purpose) }}" 
                    class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                更新する
            </button>
        </form>
    </div>
    @endsection
</x-app-layout>