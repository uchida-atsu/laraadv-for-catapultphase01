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
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border rounded">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4/12">
                                    日時
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-7/12">
                                    目的
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($upcomingReservations as $upcomingReservation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($upcomingReservation->reserved_at)->format('Y年m月d日 H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 truncate max-w-xs">
                                        {{ $upcomingReservation->purpose }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        予約はまだありません。
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <h3 class="text-lg font-bold mt-6 mb-4">履歴一覧</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border rounded">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4/12">
                                    日時
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-7/12">
                                    目的
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pastReservations as $pastReservation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($pastReservation->reserved_at)->format('Y年m月d日 H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 truncate max-w-xs">
                                        {{ $pastReservation->purpose }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        履歴はありません。
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

