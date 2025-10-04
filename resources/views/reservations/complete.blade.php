<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            完了ページ
        </h2>
    </x-slot>

    <div>
        @if (session('completed'))
            <div class="flex flex-col items-center justify-center min-h-screen">
                <div class="text-5xl">予約が完了しました！</div>
                
                <br> <div class="flex flex-col items-center justify-center">
                    <p class="text-2xl font-bold text-center">予約内容</p>
                    @foreach ($reservations as $reservation)
                        <p class="text-center">日時: {{ $reservation->reserved_at }}</p>
                        <p class="text-center">目的: {{ $reservation->purpose }}</p>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
