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

                <h3 class="text-lg font-bold mt-6 mb-4">予約一覧</h3>
                @if ($reservations->isEmpty())
                    <p>現在、予約はありません。</p>
                @else
                    <ul>
                        @foreach ($reservations as $reservation)
                            <li class="mb-2">
                                {{ \Carbon\Carbon::parse($reservation->reserved_at)->format('Y-m-d H:i') }}
                                ： {{ $reservation->purpose }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

