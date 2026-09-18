<x-layout>
    <x-slot name="header">
        <img class=" max-w md:max-w-full md:w-full max-h-[340px] md:max-h-[240px] object-cover object-[center_80%]" src="{{ asset('images/homepage-image.jpg') }}" alt="Samen Shoppen">
    </x-slot>

    <h1 class="text-2xl font-bold text-red-600 font-serif">Inloggen</h1>
       <form action="{{ route('login.submit') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-bold mb-2">Wachtwoord</label>
            <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
            Inloggen
        </button>
        <a href="{{ route('password.request') }}" class="ml-4 text-blue-500 hover:underline">Wachtwoord vergeten?</a>
    </form>
</x-layout>