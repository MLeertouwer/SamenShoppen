<x-layout>
    <x-slot name="header">
        <img class=" max-w md:max-w-full md:w-full max-h-[340px] md:max-h-[240px] object-cover object-[center_80%]" src="{{ asset('images/contactimage.jpg') }}" alt="contact">
    </x-slot>
     
    <h1 class="text-2xl font-bold text-red-600 font-serif">Contact</h1>
    <p class="text-indigo-900 font-serif mt-3">
        Wil je ook deel uitmaken van Samen Shoppen?
        Klik dan op de onderstaande knop om je aan te melden en toegang te krijgen tot het platform.
    </p>
        <a href="{{ route('requestform') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-4">
        Aanmelden
    </a>


</x-layout>