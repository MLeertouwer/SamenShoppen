<x-layout>
    <x-slot name="header">
        <img class=" max-w-[360px] md:max-w-full md:w-full max-h-[340px] md:max-h-[240px] object-cover object-[center_80%]" src="{{ asset('images/homepage-image.jpg') }}" alt="Samen Shoppen">
    </x-slot>

    <div class="max-w-md mx-auto mt-8 p-4 relative">

        <div class="pr-8">
            <h1 class="text-2xl font-bold text-red-600 font-serif mb-2">Nieuwe boodschappenrit aanmaken</h1>
            <p class="text-indigo-900 font-serif text-sm mb-6">
                Vul de gegevens in om je rit te plannen in de kalender, zodat andere shoppers hun boodschappen aan jou kunnen meegeven.
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

                <!-- Hidden field voor de boodschappenrit op 1 (true) -->
                <input type="hidden" name="is_grocery_only" value="1">

                <div>
                    <label for="destination-autocomplete-container" class="block font-bold mb-1">Naar welke winkel ga je?</label>
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
                    <label for="max_passengers" class="block font-bold mb-1">Hoeveel mensen mogen een boodschappenlijstje toevoegen?</label>
                    <input type="number" name="max_passengers" id="max_passengers"
                        value="{{ old('max_passengers', 1) }}" min="1"
                        class="w-full p-2 border-2 border-red-500 outline-none text-indigo-900" required>
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
                // BESTEMMING
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
                        // Sla op in de hidden fields
                        document.getElementById('destination_latitude').value = place.location.lat();
                        document.getElementById('destination_longitude').value = place.location.lng();
                        document.getElementById('destination_address').value = place.formattedAddress;
                        document.getElementById('destination_store').value = place.displayName;
                    }
                });

            } catch (error) {
                console.error('Google Maps kon niet worden geladen:', error);
            }
        }

        window.addEventListener('load', initAutocomplete);
    </script>
</x-layout>