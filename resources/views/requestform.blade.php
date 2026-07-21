<x-layout>

<x-slot name="header">
    <img class="max-w md:max-w-full md:w-full max-h-[340px] md:max-h-[240px] object-cover object-[center_80%]" src="{{ asset('images/signup.jpg') }}" alt="contact">
</x-slot>

  <h1 class="text-2xl font-bold text-red-600 font-serif">Aanmelden</h1>
    <p class="text-indigo-900 font-serif mt-3">
        Leuk dat je je wilt aanmelden bij Samen Shoppen! Vul het onderstaande formulier in om een account aan te maken en deel te nemen aan onze gemeenschap van shoppers.
   </p>

   @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded max-w-md mx-auto mt-6">
        {{ session('success') }}
    </div>
   @endif

 <form class="bg-gray-100 rounded-lg shadow-md p-6 max-w-md mx-auto mt-6" method="POST" action="{{ route('form.submit') }}">
    @csrf

   <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Naam</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Bijv. John Doe" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-blue-500 focus:outline-none">
        @error('name')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label name="email" class="block text-gray-700 text-sm font-bold mb-2">E-mailadres</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Bijv. john.doe@example.com" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-blue-500 focus:outline-none">
        @error('email')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label name="address" class="block text-gray-700 text-sm font-bold mb-2">Adres</label>
        <input type="text" name="address" value="{{ old('address') }}" placeholder="Bv. Hoofdstraat 1, 1234AB Amsterdam" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-blue-500 focus:outline-none">
        @error('address')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label name="phone" class="block text-gray-700 text-sm font-bold mb-2">Telefoonnummer</label>
        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="06-123456789" class="w-full border border-gray-300 rounded px-3 py-2 focus:border-blue-500 focus:outline-none">
        @error('phone')
            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">
        Inloggegevens Aanvragen
    </button>

</form>
</x-layout>