<x-layout>
    <x-slot name="header">
        Mijn profiel
    </x-slot>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded mt-6 hover:bg-red-700">
        Uitloggen
    </button>
</form>
</x-layout>