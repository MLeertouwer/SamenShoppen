<x-slot name="header"></x-slot>
<div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8 space-y-4">

    <!-- Header -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Chat naam en adres -->
        <div>
            <h1 class="text-lg font-bold text-gray-900">Groepschat Rit</h1>
            <p class="text-xs text-gray-500">{{ $ride->destination_store }} - {{ $ride->destination_address }}</p>
        </div>

        <!-- Terugknop -->
        <a href="{{ route('ritten.show', $ride->id) }}" class="inline-flex items-center justify-center text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700 px-4 py-2 rounded-xl shadow-xs transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Terug naar rit
        </a>
    </div>

    <!-- Chat -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 h-[60vh] flex flex-col justify-between">

        <!-- Container met de berichten -->
        <div wire:poll.2s class="overflow-y-auto space-y-3 pr-2 flex flex-col-reverse">
            <div class="space-y-3">
                @foreach($messages as $message)
                @php
                // Check of het bericht van de ingelogde user is voor styling
                $isMe = $message->membership->user_id === auth()->id();
                @endphp

                <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                    <div class="flex flex-row">
                        <span class="text-[10px] text-gray-400 mb-1 px-1">
                            {{ $message->membership->user->name }}
                        </span>
                        <span class="text-[10px] text-gray-400 mb-1 px-1">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>

                    <div wire:key="message-{{ $message->id }}" class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} group relative">
                        {{-- De eigenlijke berichtballon (alleen de tekst en tijd) --}}
                        <div class="max-w-[100%] rounded-2xl px-4 py-2 text-sm shadow-xs {{ $isMe ? 'bg-orange-600 text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                            <p>{{ $message->message_text }}</p>
                        </div>

                        {{-- De verwijderknop staat nu buiten de ballon, maar binnen de group --}}
                        @role('beheerder')
                        <button wire:click="deleteMessage({{ $message->id }})" class="text-xs text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity">
                            Verwijderen
                        </button>
                        @endrole
                    </div>
                </div>

                @endforeach
            </div>
        </div>

        <!-- Bericht versturen -->
        <form wire:submit="sendMessage" class="mt-4 pt-3 border-t border-gray-100 flex gap-2">
            <!-- Input field met een wire:key om ervoor te zorgen dat het veld geleegd word zodra het bericht is verstuurd -->
            <input type="text" wire:model="messageText" placeholder="Typ een bericht..." wire:key="input-{{ $formKey }}" class="flex-1 border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold px-4 py-2 rounded-xl text-sm transition-colors shadow-xs flex items-center justify-center">
                Verstuur
            </button>
        </form>

    </div>
</div>