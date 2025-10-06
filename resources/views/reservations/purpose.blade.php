<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約画面　2/2（目的入力）
        </h2>
    </x-slot>
    <div>
        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf

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
                        @foreach ($selectedSlots as $slot)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <input name="reserved_at[]" type="hidden" value="{{ $slot }}">
                                    {{ \Carbon\Carbon::parse($slot)->format('Y年m月d日 H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <input name="purpose[]" id="purpose" required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- {{-- 使用目的 --}}
            <div class="form-group">
                <label for="purpose">使用目的</label>
                <input name="purpose" id="purpose" required class="form-input">
            </div> -->
            <br>
            <!-- 完了ボタン -->
            <div style="text-align: center; margin-bottom: 8px;">
                <button type="submit" class="reservation-button">予約を完了する</button>
            </div>
        </form>
    </div>
</x-app-layout>