<x-layout>
  <x-slot name="header">
    <img class=" max-w md:max-w-full md:w-full max-h-[340px] md:max-h-[240px] object-cover object-[center_80%]" src="{{ asset('images/homepage-image.jpg') }}" alt="Samen Shoppen">
  </x-slot>
    <h1 class="text-2xl font-bold text-red-600 font-serif">Samen Shoppen</h1>
    <p class="text-indigo-900 font-serif mt-3">
        Heb je geen vervoer, heb je geen tijd of wil je liever niet alleen winkelen?
    </p>

    <h2 class="text-xl text-red-600 font-bold font-serif mt-6">Wat biedt samen shoppen?</h2>
    
    <ul class="text-indigo-900 font-serif mt-2">
        <li class="list-disc list-inside">Meerijden met andere shoppers</li>
        <li class="list-disc list-inside">Gedeelde kosten bij winkelbezoek</li>
        <li class="list-disc list-inside">Boodschappen laten halen</li>
        <li class="list-disc list-inside">Samen winkelen</li>
    </ul> 

    <h2 class="text-xl text-red-600 font-bold font-serif mt-6">De filosofie van samen shoppen?</h2>
    <p class="text-indigo-900 font-serif mt-3 mb-6">
        Samen duurt het langst en gedeelde kosten zijn halve kosten
    </p>

    <h2 class="text-xl text-red-600 font-bold font-serif mt-6 mb-6">Heb je al een account?</h2>


    <btn class="bg-red-600 text-white font-bold py-2 px-4 rounded mt-6 hover:bg-red-700">
        <a href="{{ route('login') }}">Inloggen</a>
    </btn>  

</x-layout>