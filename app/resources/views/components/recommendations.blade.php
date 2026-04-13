@if($items->isNotEmpty())
<div class="mt-12 pb-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h4 class="text-xl font-semibold">Recommendations</h4>

        <x-meals-grid :items="$items"/>

    </div>
</div>
@endif
