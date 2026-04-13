@props(['items'])
<div class="flex flex-wrap -mx-1 lg:-mx-4">

    @foreach($items as $item)
        <x-meal-card :item="$item"/>
    @endforeach

</div>
