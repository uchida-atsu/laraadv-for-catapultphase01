<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            完了ページ
        </h2>
    </x-slot>
    <br>
    <div class="flex items-center justify-center text-4xl">
        予約が完了しました！
    </div>
    <br>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8 sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">
                            日時
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-5/12">
                            目的
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($reservation->reserved_at)->format('Y年m月d日 H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 truncate max-w-xs">
                                {{ $reservation->purpose }}
                            </td>
                        </tr>   
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
