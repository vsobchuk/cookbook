@props(['searchQuery' => ''])
<form action="{{ route('search') }}"
      method="GET"
      class="w-full flex flex-row">
    <input name="q"
           placeholder="{{ __('Meal\'s name') }}"
           value="{{ $searchQuery }}"
           class="w-full mr-4 px-2 rounded-md border border-gray-300 focus:border-indigo-300 outline-none focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

    <x-button>
        {{ __('Search') }}
    </x-button>
</form>
