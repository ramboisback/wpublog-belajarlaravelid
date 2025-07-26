<x-layout :title="$title" :nav="$nav">
    <p>{{ $isi }}</p>
    <div class="flex mt-3">
        @for($i = 1; $i <= 10; $i++)
            {{-- genap --}}
            @if ($i % 2 === 0)    
                <div class="w-8 h-8 bg-blue-500 text-white p-0 me-1 text-xs grid place-items-center">{{ $i }}</div>
            @endif
        @endfor
    </div>
</x-layout>