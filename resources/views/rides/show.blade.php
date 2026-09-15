<x-layout>
    <x-slot name="header"></x-slot>

    <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">

        <div class="flex justify-between items-center mb-6">
            <!-- Terug-->
            <a href="{{ route('ritten.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Terug
            </a>

            <!-- Chat -->
            @if($ride->approvedPassengers()->where('user_id', auth()->id())->exists() || $ride->driver()->where('user_id', auth()->id())->exists())
            <a href="{{ route('ritten.chat', $ride->id) }}" class="inline-flex items-center text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700 px-4 py-2 rounded-xl shadow-xs transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Groepschat
            </a>
            @endif
        </div>

        <!-- Success/Error Meldingen -->
        @if (session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-sm font-semibold rounded-xl flex items-center space-x-2 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if (session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-sm font-semibold rounded-xl flex items-center space-x-2 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Rit Details -->
        <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 flex flex-col gap-6">

            <!-- Rit badges -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100">
                <div>
                    @if($isGrocery ?? false)
                    <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap inline-flex items-center gap-1">
                        🛒 Boodschappenrit
                    </span>
                    @else
                    <span class="bg-orange-100 text-orange-800 border border-orange-200 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap inline-flex items-center gap-1">
                        🚗 Passagiersrit
                    </span>
                    @endif
                </div>

                <!-- Status badges -->
                <div class="flex items-center gap-1.5 w-fit">
                    @if(($ride->status->value ?? $ride->status) === 'verlopen' || \Carbon\Carbon::parse($ride->departure_time)->isPast())
                    <span class="bg-gray-100 text-gray-600 border border-gray-300 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                        Afgelopen
                    </span>
                    @elseif(($ride->status->value ?? $ride->status) === 'vol' || ($ride->max_passengers - $ride->approvedPassengersCount()) <= 0)
                        <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                        Vol
                        </span>
                        @else
                        <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                            Open
                        </span>
                        @endif

                        <span class="bg-black text-white text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                            {{ $ride->max_passengers - $ride->approvedPassengersCount() }} {{ ($ride->max_passengers - $ride->approvedPassengersCount()) == 1 ? 'plek' : 'plekken' }} over
                        </span>
                </div>
            </div>

            <!-- Informatie & Locatie -->
            <div class="space-y-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $ride->destination_store }}
                    </h1>
                    <p class="text-xs font-medium text-gray-500 mt-0.5">
                        {{ $ride->destination_address }}
                    </p>
                </div>

                <div class="space-y-2 pt-2 text-sm text-gray-600">
                    @unless ($isGrocery)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Vertreklocatie: <strong class="text-gray-800 font-semibold">{{ $ride->departure_address }}</strong></span>
                    </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Aangemaakt door: <strong class="text-gray-800 font-semibold">{{ $ride->driver->user->name }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Datum & Tijd Blokken -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-gray-100">
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                    <div class="p-2 bg-white text-orange-600 rounded-lg shadow-sm flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 3V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Datum</p>
                        <p class="text-xs md:text-sm font-semibold text-gray-800 truncate">
                            {{ \Carbon\Carbon::parse($ride->departure_time)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                    <div class="p-2 bg-white text-orange-600 rounded-lg shadow-sm flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Tijd</p>
                        <p class="text-xs md:text-sm font-semibold text-gray-800 truncate">
                            {{ \Carbon\Carbon::parse($ride->departure_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actieknoppen & Pop-up Modal -->
            <div x-data="{ openModal: false }"
                x-init="$watch('openModal', value => { 
         if (value) { 
             $nextTick(() => { 
                 setTimeout(() => initModalAutocomplete(), 150); 
             }); 
         } 
     })">
                @if($isDriver)
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-center text-sm font-medium text-gray-600">
                    Je bent de bestuurder van deze rit
                </div>
                @elseif(($ride->status->value ?? $ride->status) === 'verlopen' || \Carbon\Carbon::parse($ride->departure_time)->isPast())
                <button type="button" disabled class="w-full bg-gray-100 text-gray-400 py-3 px-4 rounded-xl font-bold text-sm cursor-not-allowed border border-gray-200">
                    <span>Rit is verlopen</span>
                </button>
                @elseif ($currentUserStatus === \App\Enums\PassengerStatus::APPROVED->value)
                <button type="button" disabled class="w-full bg-green-50 text-green-700 py-3 px-4 rounded-xl font-bold text-sm cursor-not-allowed border border-green-200">
                    <span>Je reist mee met deze rit!</span>
                </button>
                @elseif ($currentUserStatus === \App\Enums\PassengerStatus::PENDING->value)
                <button type="button" disabled class="w-full bg-amber-50 text-amber-600 py-3 px-4 rounded-xl font-bold text-sm cursor-not-allowed border border-amber-200">
                    <span>Verzoek is in behandeling</span>
                </button>
                @elseif (($ride->status->value ?? $ride->status) === 'vol')
                <button type="button" disabled class="w-full bg-gray-100 text-gray-400 py-3 px-4 rounded-xl font-bold text-sm cursor-not-allowed border border-gray-200">
                    <span>Helaas, deze rit is vol!</span>
                </button>
                @else
                <div class="grid grid-cols-2 gap-2">
                    <!-- 1. Gewone ritaanvraag (Direct in 1 klik) -->
                    <form action="{{ route('ritten.join', $ride->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-[#0d0e1c] text-white py-3 px-2 rounded-xl font-bold text-xs sm:text-sm hover:bg-black transition-all shadow-sm text-center">
                            <span>Ik wil meerijden</span>
                        </button>
                    </form>

                    <!-- 2. Aanvraag mét boodschappenlijst (Opent de modal) -->
                    <button @click="openModal = true" type="button" class="w-full bg-white text-[#0d0e1c] border border-[#0d0e1c] py-3 px-2 rounded-xl font-bold text-xs sm:text-sm hover:bg-gray-50 transition-all shadow-sm text-center">
                        <span>Boodschappen</span>
                    </button>
                </div>
                @endif


                <!-- Pop-up Modal -->
                <div x-show="openModal"
                    x-transition:enter="transition-opacity ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 overflow-y-auto"
                    style="display: none;">

                    <!-- Donkere overlay -->
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="openModal = false"></div>

                    <!-- Modal Content Container -->
                    <div class="flex min-h-full items-start md:items-center justify-center p-4 pt-10 md:pt-4">
                        <div class="relative bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl space-y-5 z-10" @click.stop>

                            <!-- Header -->
                            <div class="border-b border-gray-100 pb-3">
                                <h3 class="text-lg font-bold text-gray-900">
                                    Aanmelden voor boodschappenrit
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Vul je boodschappenlijst en afleveradres in voor de bestuurder.
                                </p>
                            </div>

                            <!-- Formulier voor Aanmelding -->
                            <form action="{{ route('ritten.join', $ride->id) }}" method="POST" class="space-y-4">
                                @csrf

                                <!-- Boodschappenlijst -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <h2 class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                            <span>Boodschappenlijst <span class="text-red-500">*</span></span>
                                        </h2>
                                        <span class="text-[11px] text-gray-400 font-medium">max. 10 artikelen</span>
                                    </div>

                                    <div x-data="{ items: [ { name: '', quantity: '' } ] }" class="space-y-2.5">

                                        <div class="max-h-36 overflow-y-auto pr-1 space-y-2.5 border border-gray-100 p-2 rounded-xl bg-gray-50/50">
                                            <template x-for="(item, index) in items" :key="index">
                                                <div class="flex items-center gap-2">
                                                    <input
                                                        type="text"
                                                        :name="`items[${index}][name]`"
                                                        x-model="item.name"
                                                        placeholder="Artikel (bijv. Melk)"
                                                        required
                                                        class="w-full bg-white rounded-lg border border-gray-300 text-sm p-2 text-gray-900 focus:border-black focus:ring-1 focus:ring-black transition-all">

                                                    <input
                                                        type="number"
                                                        :name="`items[${index}][quantity]`"
                                                        x-model="item.quantity"
                                                        placeholder="Aantal"
                                                        class="w-1/3 bg-white rounded-lg border border-gray-300 text-sm p-2 text-gray-900 focus:border-black focus:ring-1 focus:ring-black transition-all">

                                                    <button
                                                        type="button"
                                                        @click="items.splice(index, 1)"
                                                        x-show="items.length > 1"
                                                        class="p-1.5 text-gray-400 hover:text-red-600 transition-colors"
                                                        title="Artikel verwijderen">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>

                                        <button
                                            type="button"
                                            x-show="items.length < 10"
                                            @click="if (items.length < 10) items.push({ name: '', quantity: '' })"
                                            class="inline-flex items-center text-xs font-bold text-orange-600 hover:text-orange-800 pt-0.5">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Extra artikel toevoegen
                                        </button>
                                    </div>
                                </div>

                                <!-- Afleveradres -->
                                <div class="pt-2 border-t border-gray-100">
                                    <h2 class="text-xs font-bold text-gray-900 flex items-center gap-1.5 mb-1.5">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>Afleveradres <span class="text-red-500">*</span></span>
                                    </h2>

                                    <div id="modal-address-autocomplete-container" class="grey-border w-full relative"></div>
                                    <input type="hidden" name="delivery_address" id="delivery_address" required>
                                </div>

                                <!-- Opmerkingen -->
                                <div class="pt-2 border-t border-gray-100">
                                    <h2 class="text-xs font-bold text-gray-900 flex items-center gap-1.5 mb-1.5">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        <span>Opmerking voor bestuurder <span class="text-gray-400 font-normal lowercase">(optioneel)</span></span>
                                    </h2>
                                    <textarea name="note" id="note" rows="2" placeholder="Bijv. Aanbellen bij nummer 12..." class="w-full bg-white rounded-lg border border-gray-300 text-sm p-2 text-gray-900 focus:border-black focus:ring-1 focus:ring-black transition-all"></textarea>
                                </div>

                                <input type="hidden" name="is_delivery_request" value="0">

                                <!-- Actieknoppen -->
                                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                                    <button type="button" @click="openModal = false" class="px-4 py-2.5 text-xs font-bold text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-100 transition">
                                        Annuleren
                                    </button>
                                    <button type="submit" class="bg-[#0d0e1c] text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-black transition shadow-sm">
                                        Bevestig Aanmelding
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Passagiers & Aanmeldingen -->
        <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 space-y-6">

            <!-- Goedgekeurde Passagiers -->
            <div>
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Passagiers ({{ $ride->passengers->where('pivot.status', \App\Enums\PassengerStatus::APPROVED->value)->count() }})</span>
                </h2>

                <div class="space-y-2">
                    @forelse($ride->passengers->where('pivot.status', \App\Enums\PassengerStatus::APPROVED->value) as $passenger)

                    @php
                    // Haal de boodschappenlijst op via de pivot ID
                    $shoppingList = \App\Models\ShoppingList::where('ride_passenger_id', $passenger->pivot->id)->first();
                    @endphp

                    <div class="p-2.5 bg-gray-50 rounded-xl text-xs font-semibold text-gray-700 space-y-2 mb-2">
                        <div class="flex items-start justify-between gap-2">
                            <div class="max-w-[50%]">
                                <span class="font-bold text-sm text-gray-900 block">{{ $passenger->user->name }}</span>

                                @if($isDriver || $passenger->id === auth()->id())
                                @if(!empty($passenger->pivot->delivery_address))
                                <span class="text-xs text-gray-600 block mt-0.5 font-normal">
                                    <strong>Afleveradres:</strong> {{ $passenger->pivot->delivery_address }}
                                </span>
                                @endif

                                @if($shoppingList && !empty($shoppingList->note))
                                <span class="text-xs text-gray-600 block mt-0.5 font-normal">
                                    <strong>Opmerking:</strong> {{ $shoppingList->note }}
                                </span>
                                @endif
                                @endif
                            </div>

                            {{-- Status badge (blijft altijd rechtsboven) --}}
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-md">Goedgekeurd</span>

                                {{-- DESKTOP KNOP: Alleen zichtbaar op schermen vanaf 'sm' en groter, direct onder de badge --}}
                                @if($shoppingList && ($isDriver || $passenger->id === auth()->id()))
                                <div class="hidden sm:block">
                                    <a href="{{ route('ritten.shopping-lists.show', ['ride' => $ride->id, 'shoppingList' => $shoppingList->id]) }}"
                                        class="inline-flex items-center gap-1 text-[11px] bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 font-medium px-2.5 py-1 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                                        </svg>
                                        Bekijk boodschappenlijst
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- MOBIELE KNOP: Alleen zichtbaar op mobiel, onderaan de kaart --}}
                        @if($shoppingList && ($isDriver || $passenger->id === auth()->id()))
                        <div class="pt-1 block sm:hidden">
                            <a href="{{ route('ritten.shopping-lists.show', ['ride' => $ride->id, 'shoppingList' => $shoppingList->id]) }}"
                                class="inline-flex items-center gap-1 text-[11px] bg-white border border-gray-300 hover:bg-gray-100 text-gray-800 font-medium px-2.5 py-1 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                                </svg>
                                Bekijk boodschappenlijst
                            </a>
                        </div>
                        @endif
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic">Nog geen passagiers aangemeld.</p>
                    @endforelse
                </div>
            </div>

            <!-- Openstaande Verzoeken (Alleen zichtbaar voor de bestuurder) -->
            @if($isDriver)
            <div class="border-t border-gray-100 pt-5">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>In afwachting ({{ $ride->passengers->where('pivot.status', \App\Enums\PassengerStatus::PENDING->value)->count() }})</span>
                </h2>

                <div class="space-y-3">
                    @forelse($ride->passengers->where('pivot.status', \App\Enums\PassengerStatus::PENDING->value) as $passenger)

                    @php
                    // Haal de boodschappenlijst op via de pivot ID
                    $shoppingList = \App\Models\ShoppingList::where('ride_passenger_id', $passenger->pivot->id)->first();
                    @endphp



                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-amber-50/50 border border-amber-200/60 rounded-xl gap-3">
                        <div class="max-w-[50%]">
                            <span class="font-bold text-sm text-gray-900 block">{{ $passenger->user->name }}</span>

                            @if($isDriver)
                            <!-- Afleveradres (Alleen tonen als het een boodschappenrit is -->
                            @if(!empty($passenger->pivot->delivery_address))
                            <span class="text-xs text-gray-600 block mt-0.5">
                                <strong>Afleveradres:</strong> {{ $passenger->pivot->delivery_address }}
                            </span>
                            @endif

                            <!-- Opmerking voor de bestuurder -->
                            @if($shoppingList && !empty($shoppingList->note))
                            <span class="text-xs text-gray-600 block mt-0.5">
                                <strong>Opmerking:</strong> {{ $shoppingList->note }}
                            </span>
                            @endif
                            @endif
                        </div>

                        <!-- Actieknoppen voor Bestuurder -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                            <!-- Bekijk boodschappenlijst button -->
                            @if($shoppingList)
                            <a href="{{ route('ritten.shopping-lists.show', ['ride' => $ride->id, 'shoppingList' => $shoppingList->id]) }}"
                                class="px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs font-bold transition shadow-xs flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                                </svg>
                                Lijst bekijken
                            </a>
                            @endif

                            <!-- Rij voor Afkeuren en Goedkeuren -->
                            <div class="flex items-center gap-2">
                                <!-- Afkeuren -->
                                <form action="{{ route('rides.passengers.update', [$ride->id, $passenger->id]) }}" method="POST" class="flex-1 sm:flex-initial">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ \App\Enums\PassengerStatus::REJECTED->value }}">
                                    <button type="submit" class="w-full sm:w-auto px-3 py-1.5 bg-white text-red-600 hover:bg-red-50 border border-red-200 rounded-lg text-xs font-bold transition shadow-xs text-center">
                                        Afkeuren
                                    </button>
                                </form>

                                <!-- Goedkeuren -->
                                <form action="{{ route('rides.passengers.update', [$ride->id, $passenger->id]) }}" method="POST" class="flex-1 sm:flex-initial">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ \App\Enums\PassengerStatus::APPROVED->value }}">
                                    <button type="submit" class="w-full sm:w-auto px-3 py-1.5 bg-[#0d0e1c] text-white hover:bg-black rounded-lg text-xs font-bold transition shadow-xs text-center">
                                        Goedkeuren
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic">Er zijn momenteel geen verzoeken in behandeling.</p>
                    @endforelse
                </div>
            </div>
            @endif

        </div>

    </div>

    <!-- JavaScript voor het inladen van de Google PlaceAutocompleteElement library -->
    <script>
        function loadGoogleMaps() {
            return new Promise((resolve, reject) => {
                if (typeof google !== 'undefined' && google.maps) {
                    resolve(google.maps);
                    return;
                }

                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=${encodeURIComponent('{{ env("MIX_GOOGLE_MAPS_API_KEY") }}')}&libraries=places&v=weekly`;
                script.async = true;
                script.defer = true;
                script.onload = () => resolve(google.maps);
                script.onerror = (error) => reject(error);
                document.head.appendChild(script);
            });
        }

        async function initModalAutocomplete() {
            try {
                await loadGoogleMaps();
                const {
                    PlaceAutocompleteElement
                } = await google.maps.importLibrary('places');

                const container = document.getElementById('modal-address-autocomplete-container');
                if (!container) return;

                if (container.querySelector('gmp-place-autocomplete')) {
                    return;
                }

                const addressAutocomplete = new PlaceAutocompleteElement({
                    includedRegionCodes: ['nl'],
                });

                container.appendChild(addressAutocomplete);

                addressAutocomplete.addEventListener('gmp-select', async ({
                    placePrediction
                }) => {
                    const place = placePrediction.toPlace();
                    await place.fetchFields({
                        fields: ['formattedAddress']
                    });
                    if (place.formattedAddress) {
                        document.getElementById('delivery_address').value = place.formattedAddress;
                    }
                });

                addressAutocomplete.addEventListener('input', (e) => {
                    document.getElementById('delivery_address').value = e.target.value || '';
                });

            } catch (error) {
                console.error('Google Maps kon niet geladen worden:', error);
            }
        }
    </script>
</x-layout>