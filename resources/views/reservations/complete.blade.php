<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('予約完了ページ') }}
    </h2>

    <div>
        @if (session('completed'))
            <h3>予約が完了しました！</h3>
            <p>日時: {{ session('reserved_at') }}</p>
            <p>目的: {{ session('purpose') }}</p>
        @endif
    </div>
</x-app-layout>
