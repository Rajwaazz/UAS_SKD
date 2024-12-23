<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-2">
                @foreach ($film as $item)
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-2 mb-4">
                        <img src="{{ asset('storage/' . $item->poster) }}" class="w-full h-64 object-cover"
                            alt="{{ $item->judul }}">
                        <div class="p-4">
                            <h5 class="text-lg font-semibold mb-2">{{ $item->judul }}</h5>
                            <p class="text-sm mb-2">{{ date('d F Y', strtotime($item->tanggalRilis)) }}</p>
                            <div class="flex justify-between items-center mb-2">
                                <span class="bg-blue-500 text-xs font-bold px-2 py-1 rounded">{{ $item->genre }}</span>
                            </div>
                            <a href="{{ route('user.film.show', $item->id) }}"
                                class="block w-full bg-blue-500 text-center px-4 py-2 rounded-md hover:bg-blue-600">Lihat
                                Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $film->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
