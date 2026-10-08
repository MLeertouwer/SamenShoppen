<x-layout>
    <x-slot name="header">
        Mijn profiel
    </x-slot>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Gebruikersinformatie</h2>
        <p><strong>Naam:</strong> {{ Auth::user()->name }}</p>
        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        <p><strong>Status:</strong> {{ Auth::user()->status }}</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h2 class="text-xl font-semibold mt-6 mb-4">Onderhoudsbijdrage</h2>
        <p>Je onderhoudsbijdrage bedraagt €10,00 per half jaar.</p>

        <form action="{{ route('payment.checkout') }}" method="POST">
            @csrf
            <button type="submit" class="mt-5 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Betaal Onderhoudsbijdrage (€ 10,00)
            </button>
        </form>
    </div>

            @if (session('success'))
                <div class="mt-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-4 p-4 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif
        

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded mt-6 hover:bg-red-700">
        Uitloggen
    </button>
</form>
</x-layout>