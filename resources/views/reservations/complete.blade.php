<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            完了ページ
        </h2>
    </x-slot>

    <div>
        @if (session('completed'))
            <h3>予約が完了しました！</h3>
            <p>日時: {{ session('reserved_at') }}</p>
            <p>目的: {{ session('purpose') }}</p>
        @endif
    </div>
</x-app-layout>
