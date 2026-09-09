<x-layout>
    <x-slot name="header"></x-slot>

    <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">

        <!-- Terug knop -->
        <a href="{{ route('ritten.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Terug
        </a>

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
                    <span class="bg-blue-100 text-blue-800 border border-blue-200 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap inline-flex items-center gap-1">
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
                    <div class="p-2 bg-white text-blue-600 rounded-lg shadow-sm flex-shrink-0">
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
                    <div class="p-2 bg-white text-blue-600 rounded-lg shadow-sm flex-shrink-0">
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
            <div x-data="{ openModal: false }" x-init="$watch('openModal', value => { if (value) { setTimeout(() => initModalAutocomplete(), 50); } })">
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
                @if($isGrocery)
                <!-- Boodschappenrit: Knop opent de modal voor het afleveradres -->
                <button @click="openModal = true" type="button" class="w-full bg-[#0d0e1c] text-white py-3 px-4 rounded-xl font-bold text-sm hover:bg-black transition-all shadow-sm">
                    <span>{{ $currentUserStatus === \App\Enums\PassengerStatus::REJECTED->value ? 'Opnieuw verzoek indienen' : 'Meld je aan' }}</span>
                </button>
                @else
                <!-- Passagiersrit: Direct verzoek versturen zonder modal -->
                <form action="{{ route('ritten.join', $ride->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-[#0d0e1c] text-white py-3 px-4 rounded-xl font-bold text-sm hover:bg-black transition-all shadow-sm">
                        <span>{{ $currentUserStatus === \App\Enums\PassengerStatus::REJECTED->value ? 'Opnieuw verzoek indienen' : 'Meld je aan' }}</span>
                    </button>
                </form>
                @endif
                @endif

                @if($isGrocery)
                <!-- Pop-up Modal  -->
                <div x-show="openModal"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="fixed inset-0 z-50 overflow-y-auto"
                    style="display: none;">

                    <!-- Donkere overlay -->
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="openModal = false"></div>

                    <!-- Modal content -->
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative bg-white rounded-2xl max-w-md w-full p-5 md:p-6 shadow-xl space-y-4 z-10" @click.stop>
                            <div>
                                <h3 class="text-base md:text-lg font-bold text-gray-900">Waar moeten we afleveren?</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Vul je adres in, zodat de bestuurder weet waar de boodschappen heen moeten.
                                </p>
                            </div>

                            <form action="{{ route('ritten.join', $ride->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                        Afleveradres <span class="text-red-500">*</span>
                                    </label>
                                    <div id="modal-address-autocomplete-container" class="w-full"></div>
                                    <input type="hidden" name="delivery_address" id="delivery_address" required>
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                                    <button type="button" @click="openModal = false" class="px-4 py-2.5 text-xs font-bold text-gray-600 hover:text-gray-900 rounded-xl hover:bg-gray-100 transition">
                                        Annuleren
                                    </button>
                                    <button type="submit" class="bg-[#0d0e1c] text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-black transition shadow-sm">
                                        Verzoek versturen
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
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
                    <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-xl text-xs font-semibold text-gray-700">
                        <div>
                            <span>{{ $passenger->user->name }}</span>
                            @if($isGrocery && !empty($passenger->pivot->delivery_address))
                            <span class="text-xs text-gray-600 block mt-0.5 font-normal">
                                <strong>Afleveradres:</strong> {{ $passenger->pivot->delivery_address }}
                            </span>
                            @endif
                        </div>

                        <!-- Status badge -->
                        <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-md">Goedgekeurd</span>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic">Nog geen passagiers aangemeld.</p>
                    @endforelse
                </div>
            </div>

            <!-- Openstaande Verzoeken (Alleen zichtbaar voor de driver) -->
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
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-amber-50/50 border border-amber-200/60 rounded-xl gap-3">
                        <div>
                            <span class="font-bold text-sm text-gray-900 block">{{ $passenger->user->name }}</span>

                            @if($isGrocery && !empty($passenger->pivot->delivery_address))
                            <span class="text-xs text-gray-600 block mt-0.5">
                                <strong>Afleveradres:</strong> {{ $passenger->pivot->delivery_address }}
                            </span>
                            @endif
                        </div>

                        <!-- Actieknoppen -->
                        <div class="flex items-center gap-2">
                            <!-- Afkeuren -->
                            <form action="{{ route('rides.passengers.update', [$ride->id, $passenger->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ \App\Enums\PassengerStatus::REJECTED->value }}">
                                <button type="submit" class="px-3 py-1.5 bg-white text-red-600 hover:bg-red-50 border border-red-200 rounded-lg text-xs font-bold transition shadow-xs">
                                    Afkeuren
                                </button>
                            </form>

                            <!-- Goedkeuren -->
                            <form action="{{ route('rides.passengers.update', [$ride->id, $passenger->id]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ \App\Enums\PassengerStatus::APPROVED->value }}">
                                <button type="submit" class="px-3 py-1.5 bg-[#0d0e1c] text-white hover:bg-black rounded-lg text-xs font-bold transition shadow-xs">
                                    Goedkeuren
                                </button>
                            </form>
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

    <style>
        /* Wtte achtergrond voor Google autocomplete veld */
        #modal-address-autocomplete-container gmp-place-autocomplete,
        gmp-place-autocomplete {
            --gmp-md-sys-color-surface: #ffffff !important;
            --gmp-md-sys-color-on-surface: #1f2937 !important;
            --gmp-md-sys-color-outline: #e5e7eb !important;
            color-scheme: light !important;


        }
    </style>

    <!-- JavaScript voor Google Maps Autocomplete -->
    <script>
        function loadGoogleMaps() {
            return new Promise((resolve, reject) => {
                if (typeof google !== 'undefined' && google.maps) {
                    resolve(google.maps);
                    return;
                }

                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=${encodeURIComponent('{{ env("MIX_GOOGLE_MAPS_API_KEY") }}')}&v=beta`;
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

                if (container && container.children.length === 0) {
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
                }
            } catch (error) {
                console.error('Google Maps kon niet worden geladen:', error);
            }
        }
    </script>
</x-layout>