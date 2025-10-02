<x-app-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">自分の予約一覧</h2>

        <form method="POST" action="{{ route('reservations.bulk-delete') }}" class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            @csrf
            @method('DELETE')

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">
                                <!-- チェックボックスヘッダー -->
                            </th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $upcomingReservation->id }}" class="rounded text-red-600 focus:ring-red-500">
                                </td>
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

            <div class="mt-6 p-4 bg-white border-t border-gray-200 flex justify-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-150 ease-in-out disabled:opacity-50"
                        onclick="return confirm('選択した予約を全て削除します。よろしいですか？')">
                    選択した予約を削除
                </button>
            </div>
        </form>
    </div>
</x-app-layout>