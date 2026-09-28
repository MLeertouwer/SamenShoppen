<x-layout>
    <x-slot name="header">
        <img class=" max-w md:max-w-full md:w-full max-h-[340px] md:max-h-[240px] object-cover object-[center_80%]" src="{{ asset('images/contactimage.jpg') }}" alt="contact">
    </x-slot>
     
    <h1 class="text-2xl font-bold text-red-600 font-serif">Contact</h1>
    <p class="text-indigo-900 font-serif mt-3 mb-3">
        Heb je suggesties, vragen of opmerkingen? Vul het onderstaande formulier in en wij nemen zo snel mogelijk contact met je op. We waarderen je feedback en kijken ernaar uit om van je te horen!
    </p>

       @if (session('success'))
            <div class="mb-4 text-sm text-green-600 bg-green-100 border border-green-400 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

    <form action="{{ route('suggesties.store') }}" method="POST" class="mt-6">
        @csrf
        <div class="mb-4">
            <label for="type" class="block text-gray-700 font-bold mb-2">Type:</label>
            <select name="type" id="type" class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="type">Selecteer een type</option>
                <option value="suggestie">Suggestie</option>
                <option value="klacht">Klacht</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="bericht" class="block text-gray-700 font-bold mb-2">Bericht:</label>
            <textarea name="bericht" id="bericht" rows="5" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
        </div>

        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600">Verstuur</button>
    
</x-layout>