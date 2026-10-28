<x-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-none text-heading text-center py-5">Tikkie maken</h1>
    </x-slot>

    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md mt-6">
        <form action="{{ route('request.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="receiver_id" class="block text-sm font-medium text-gray-700 mb-1">Kies een passagier</label>
                <select name="receiver_id" id="receiver_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                    <option value="">Kies een passagier...</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ (isset($receiverId) && $receiverId == $member->id) ? 'selected' : '' }}>
                            {{ $member->name }} ({{ $member->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Bedrag (€):</label>
                <input type="number" step="0.01" name="amount" id="amount" placeholder="5.00" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Omschrijving (max. 35 tekens):</label>
                <input type="text" name="description" id="description" maxlength="35" placeholder="Boodschappen kratje" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out mt-3">
                Tikkie Versturen
            </button>
        </form>
    </div>
</x-layout>