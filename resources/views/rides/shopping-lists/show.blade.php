<x-layout>
    <x-slot name="header"></x-slot>

    <div class="max-w-xl mx-auto p-4 space-y-4">
        <!-- Terugknop -->
        <div>
            <a href="{{ route('ritten.show', $ride) }}" class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 hover:text-gray-900 transition-colors">
                ← Terug naar ritoverzicht
            </a>
        </div>

        <!-- Header -->
        <div class="space-y-0.5">
            <h1 class="text-base font-bold text-gray-900">Boodschappenlijst</h1>
            @if($shoppingList->ridePassenger && $shoppingList->ridePassenger->membership && $shoppingList->ridePassenger->membership->user)
            <p class="text-xs text-gray-500">
                Van: <span class="font-medium text-gray-700">{{ $shoppingList->ridePassenger->membership->user->name }}</span>
            </p>
            @endif
        </div>

        <!-- Boodschappenlijst -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-xs p-4 space-y-3">
            <h2 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2">
                Artikelen ({{ $shoppingList->items->count() }})
            </h2>

            <div class="space-y-2">
                @forelse ($shoppingList->items as $shoppingListItem)
                <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-xl text-xs font-semibold text-gray-700">
                    <!-- Productnaam -->
                    <span class="text-gray-900 font-medium">{{ $shoppingListItem->name }}</span>

                    <!--  Quantity -->
                    <span class="bg-gray-200 text-gray-800 px-2 py-0.5 rounded-md text-[10px]">
                        {{ $shoppingListItem->quantity }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-gray-400 italic text-center py-4">Geen items gevonden op deze boodschappenlijst.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>