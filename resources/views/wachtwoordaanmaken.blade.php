<x-layout>
     <x-slot name="header">
    <h1 class="text-xl font-semibold leading-none text-heading text-center py-5">Wachtwoord aanmaken</h1>
   </x-slot>

   <div class="max-w-md mx-auto mt-8 p-6 bg-neutral-primary-soft border border-default rounded-base shadow-xs">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ request('email') }}">

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-body">Wachtwoord</label>
                <input id="password" type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand focus:ring focus:ring-brand focus:ring-opacity-50">
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-body">Bevestig wachtwoord</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand focus:ring focus:ring-brand focus:ring-opacity-50">
            </div>

           <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
              Wachtwoord aanmaken
        </button>
        </form>
    </div>
</x-layout>