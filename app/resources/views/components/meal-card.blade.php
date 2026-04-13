@props(['item'])
<!-- Column -->
<div class="my-1 px-1 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/3">

    <!-- Article -->
    <article class="overflow-hidden rounded-lg shadow-lg">

        <a href="#">
            <img alt="Placeholder" class="block h-auto w-full" src="{{ $item->imgUrl }}">
        </a>

        <header class="flex items-center justify-between leading-tight p-2 md:p-4">
            <h1 class="text-lg">
                <a class="no-underline hover:underline text-black" href="#">
                    {{ $item->title }}
                </a>
            </h1>
            <p class="text-grey-darker text-sm">
                {{ $item->category }}
            </p>
        </header>

        <footer class="flex items-center justify-between leading-none p-2 md:p-4">
            <p class="text-sm">{{ $item->country }}</p>

            <x-button-favorite :item="$item"></x-button-favorite>
        </footer>

    </article>
    <!-- END Article -->

</div>
<!-- END Column -->
