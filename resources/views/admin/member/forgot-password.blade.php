<x-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold leading-none text-heading text-center py-5">Wachtwoord vergeten</h1>
    </x-slot>

    <h2>Vul je emailadres hieronder in, als die bij ons bekend is krijg je een link doorgestuurd om je wachtwoord te herstellen</h2>
    
    <div class="max-w-md mx-auto mt-8 p-6 bg-neutral-primary-soft border border-default rounded-base shadow-xs">
        
        {{-- Succesmelding als de e-mail is verstuurd --}}
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 bg-green-100 border border-green-400 p-3 rounded">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-body">E-mailadres</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand focus:ring focus:ring-brand focus:ring-opacity-50">
                @error('email')
                    <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700 w-full">
                Link versturen
            </button>
        </form>
    </div>
</x-layout>