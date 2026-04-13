@props(['item'])
<button x-data="toggleFavorite"
        data-id="{{ $item->id }}"
        data-checked="{{ (int) $item->is_favorite }}"
        class="hover:text-red-700"
        x-on:click="onClick"
        x-bind:class="{'text-red-500': checked, 'text-stone-500': !checked}">
    <x-icons.like/>
</button>
