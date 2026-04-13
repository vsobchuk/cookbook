<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <x-search-card :search-query="$searchQuery"/>

    <div class="mt-12 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h4 class="text-xl font-semibold">Search results
                @if($searchQuery)
                    by the query "{{ $searchQuery }}":
                @endif
            </h4>

            <x-meals-grid :items="$results"/>

        </div>
    </div>
</x-app-layout>
