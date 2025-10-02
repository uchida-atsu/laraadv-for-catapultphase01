<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">ユーザー情報</h3>
                <p>名前: {{ $user->name }}</p>
                <p>メール: {{ $user->email }}</p>

                <h3 class="text-lg font-bold mt-6 mb-4 flex items-center flex-start">
                    <span>予約一覧　</span>
                    <a href="{{ route('reservations.manage') }}" class="text-blue-600 hover:underline">
                        編集する
                    </a>
                </h3>

                <ul>
                    @forelse ($upcomingReservations as $reservation)
                        <li>{{ $reservation->reserved_at }} - {{ $reservation->purpose }}</li>
                    @empty
                        <li>これからの予約はありません</li>
                    @endforelse
                </ul>
                <h3 class="text-lg font-bold mt-6 mb-4">履歴一覧</h3>
                <ul>
                    @forelse ($pastReservations as $reservation)
                        <li>{{ $reservation->reserved_at }} - {{ $reservation->purpose }}</li>
                    @empty
                        <li>履歴はありません</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>

