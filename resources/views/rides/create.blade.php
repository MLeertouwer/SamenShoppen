<x-layout>
    <x-slot name="header">
        <img class=" max-w-[360px] md:max-w-full md:w-full max-h-[340px] md:max-h-[240px] object-cover object-[center_80%]" src="{{ asset('images/homepage-image.jpg') }}" alt="Samen Shoppen">
    </x-slot>

    <div class="max-w-md mx-auto mt-8 p-4 relative">

        <div class="pr-8">
            <h1 class="text-2xl font-bold text-red-600 font-serif mb-2">Zelf rijden, rit aanmelden</h1>
            <p class="text-indigo-900 font-serif text-sm mb-6">
                Vul de gegevens in om je rit te plannen in de kalender, zodat andere shoppers zich kunnen opgeven.
            </p>

            @if ($errors->any())
            <div class="border-2 border-red-600 p-3 mb-4 rounded bg-red-50 text-red-600 font-serif text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('ritten.store') }}" method="POST" class="space-y-5 font-serif text-indigo-900">
                @csrf

                <div>
                    <label for="destination-autocomplete-container" class="block font-bold mb-1">Naar welke winkel ga je?</label>
                    <!-- Google plaatst hier automatisch de autocomplete-zoekbalk in -->
                    <div id="destination-autocomplete-container" class="w-full text-indigo-900 placeholder-indigo-300"></div>

                    <!-- Verborgen velden die via JavaScript worden gevuld voor de database -->
                    <input type="hidden" name="destination_latitude" id="destination_latitude">
                    <input type="hidden" name="destination_longitude" id="destination_longitude">
                    <input type="hidden" name="destination_address" id="destination_address">
                    <input type="hidden" name="destination_store" id="destination_store">
                </div>

                <div>
                    <label for="departure_time" class="block font-bold mb-1">Vertrekdatum & tijd</label>
                    <input type="datetime-local" name="departure_time" id="departure_time"
                        value="{{ old('departure_time') }}"
                        class="w-full p-2 border-2 border-red-500 outline-none text-indigo-900" required>
                </div>

                <div>
                    <label for="max_passengers" class="block font-bold mb-1">Hoeveel mensen kunnen er meerijden?</label>
                    <input type="number" name="max_passengers" id="max_passengers"
                        value="{{ old('max_passengers', 1) }}" min="1"
                        class="w-full p-2 border-2 border-red-500 outline-none text-indigo-900" required>
                </div>

                <div class="border-t-2 border-dashed border-indigo-200 pt-4 mt-6">
                    <h3 class="text-lg font-bold text-red-600 mb-1">Vertreklocatie</h3>
                    <p class="text-xs text-indigo-900/70 mb-3">Typ je vertrekpunt en selecteer de vertreklocatie uit de lijst.</p>

                    <div class="mb-4">
                        <label for="departure-autocomplete-container" class="block text-sm font-bold mb-1">Zoek adres of plaats</label>
                        <div id="departure-autocomplete-container"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1">Gevonden Lengtegraad</label>
                            <input type="text" name="departure_longitude" id="departure_longitude"
                                value="{{ old('departure_longitude') }}"
                                class="w-full p-2 border bg-gray-100 text-gray-500 text-xs outline-none" readonly required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1">Gevonden Breedtegraad</label>
                            <input type="text" name="departure_latitude" id="departure_latitude"
                                value="{{ old('departure_latitude') }}"
                                class="w-full p-2 border bg-gray-100 text-gray-500 text-xs outline-none" readonly required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-1">Gevonden adres</label>
                            <input type="text" name="departure_address" id="departure_address"
                                value="{{ old('departure_address') }}"
                                class="w-full p-2 border bg-gray-100 text-gray-500 text-xs outline-none" readonly required>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-start">
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-indigo-900 font-bold px-6 py-1.5 text-md font-serif shadow-sm transform -rotate-1 hover:scale-105 transition duration-150 border border-yellow-500">
                        Bevestig
                    </button>
                </div>
            </form>
        </div>
    </div>

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

        async function initAutocomplete() {
            try {
                await loadGoogleMaps();

                // Laad de places library in
                const {
                    PlaceAutocompleteElement
                } = await google.maps.importLibrary('places');

                // ==========================================
                // 1. BESTEMMING (Alleen Supermarkten) 🛒
                // ==========================================
                const destinationAutocomplete = new PlaceAutocompleteElement({
                    includedRegionCodes: ['nl'],
                    types: ['grocery_store', 'supermarket'],
                });

                const destContainer = document.getElementById('destination-autocomplete-container');
                destContainer.appendChild(destinationAutocomplete);

                destinationAutocomplete.addEventListener('gmp-select', async ({
                    placePrediction
                }) => {
                    const place = placePrediction.toPlace();
                    await place.fetchFields({
                        fields: ['location', 'formattedAddress', 'displayName']
                    });

                    if (place.location) {
                        // Sla op in de nieuwe bestemmingsvelden
                        document.getElementById('destination_latitude').value = place.location.lat();
                        document.getElementById('destination_longitude').value = place.location.lng();
                        document.getElementById('destination_address').value = place.formattedAddress;
                        document.getElementById('destination_store').value = place.displayName;

                    }
                });

                // ==========================================
                // 2. VERTREKPUNT (Alle Adressen) 🏠
                // ==========================================
                const departureAutocomplete = new PlaceAutocompleteElement({
                    includedRegionCodes: ['nl'], // Geen requestedTypes!
                });

                const depContainer = document.getElementById('departure-autocomplete-container');
                depContainer.appendChild(departureAutocomplete);

                departureAutocomplete.addEventListener('gmp-select', async ({
                    placePrediction
                }) => {
                    const place = placePrediction.toPlace();
                    await place.fetchFields({
                        fields: ['location', 'formattedAddress']
                    });

                    if (place.location) {
                        // Sla op in de vertrekpuntvelden (oude logica behouden)
                        document.getElementById('departure_latitude').value = place.location.lat();
                        document.getElementById('departure_longitude').value = place.location.lng();
                        document.getElementById('departure_address').value = place.formattedAddress;
                    }
                });

            } catch (error) {
                console.error('Google Maps kon niet worden geladen:', error);
            }
        }

        window.addEventListener('load', initAutocomplete);
    </script>
</x-layout>