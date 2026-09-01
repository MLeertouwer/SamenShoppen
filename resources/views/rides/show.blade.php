<x-layout>

    <x-slot name="header">
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">

        @php
            $plekkenOver = $ride->max_passengers - $ride->passengers->count();
        @endphp
        
        <a href="{{ route('ritten.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Terug
        </a>

        <!-- Meldingen -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 text-sm font-semibold rounded-xl flex items-center space-x-2 shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-sm font-semibold rounded-xl flex items-center space-x-2 shadow-sm animate-fade-in">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-gray-100 relative">
            
            <div class="md:absolute md:top-6 md:right-6 mb-4 md:mb-0 flex justify-start md:justify-end">
                <span class="bg-black text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                    {{ $plekkenOver }} {{ $plekkenOver == 1 ? 'plek' : 'plekken' }} over
                </span>
            </div>

            <div class="flex items-start space-x-3 mt-1">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">
                        {{ $ride->destination_store}}
                    </h1>
                    <!-- Adres -->
                    <div>
                        <p class=" text-xs font-semibold text-gray-500 mt-0.5 mb-5">
                            {{ $ride->destination_address }}
                        </p>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Aangemaakt door: <span class="font-medium text-gray-700">{{ $ride->driver->user->name }}</span>
                    </p>
                    <p class="text-sm text-gray-500 mt-1.5">
                        Vertreklocatie: <span class="font-medium text-gray-700">{{ $ride->departure_address}}</span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 my-6 pt-5 border-t border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 3V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Datum</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($ride->departure_time)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Tijd</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($ride->departure_time)->format('G:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6">
                @php
                    // Omdat de user gegarandeerd ingelogd is, kunnen we direct zoeken naar het membership
                    $userMembership = \App\Models\Membership::where('user_id', auth()->id())->where('status', 'active')->first();
                    
                    // Check of deze specifieke passagier al in de lijst staat
                    $reistAlMee = $userMembership ? $ride->passengers->contains($userMembership->id) : false;
                @endphp

        @if ($reistAlMee)
            <!-- Button blokkeren: Al aangemeld -->
            <button type="button" disabled class="w-full bg-gray-100 text-gray-400 py-3 px-4 rounded-xl font-bold text-sm cursor-not-allowed flex items-center justify-center space-x-2 border border-gray-200">
                <span>Je reist al mee!</span>
            </button>

        @elseif ($plekkenOver <= 0)
            <!-- Button blokkeren: Rit is vol -->
            <button type="button" disabled class="w-full bg-gray-100 text-gray-400 py-3 px-4 rounded-xl font-bold text-sm cursor-not-allowed flex items-center justify-center space-x-2 border border-gray-200">
                <span>Helaas, deze rit is vol!</span>
            </button>

        @else
            <!-- Actieve button -->
            <form action="{{ route('ritten.join', $ride->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-[#0d0e1c] text-white py-3 px-4 rounded-xl font-bold text-sm">
                    <span>Ik wil meerijden</span>
                </button>
            </form>
        @endif

                <button class="w-full bg-white border border-gray-200 text-gray-800 py-3 px-4 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.2 9m2.2-9h10m0 0l2.2 9m-2.2-9h2.3M7 21a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                    <span>Voeg boodschappenlijst toe</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <h2 class="text-sm font-bold text-gray-900 flex items-center space-x-2 mb-4">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span>Passagiers ({{ $ride->passengers->count() }})</span>
            </h2>
            <!-- Passagierslijst -->
            <div class="mt-3 space-y-2">
                @if($ride->passengers->isEmpty())
                    <p class="text-xs text-gray-400 italic">Nog geen passagiers aangemeld.</p>
                @else
                    @foreach($ride->passengers as $passenger)
                        <div class="flex items-center space-x-3 p-3 bg-blue-50/40 rounded-xl border border-blue-50/60">
                            <div>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $passenger->user->name }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="w-100 border-t border-gray-100 my-5"></div>

            <h2 class="text-sm font-bold text-gray-900 flex items-center space-x-2 mb-4">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{-- Counter voor het aantal verzoeken --}}
                <span>Verzoeken ({{ $ride->passengers->where('pivot.status', 'pending')->count() }})</span>
            </h2>
            <div class="mt-3 space-y-2">
                @if($ride->passengers->where('pivot.status', 'pending')->isEmpty())
                    <p class="text-xs text-gray-400 italic">Nog geen verzoeken.</p>
                @else
                    @foreach($ride->passengers->where('pivot.status', 'pending') as $verzoek)
                        {{-- Op mobiel onder elkaar (flex-col), vanaf 'sm' schermen naast elkaar (sm:flex-row) --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 bg-blue-50/40 rounded-xl border border-blue-50/60">
                            
                            {{-- Links: Naam van de passagier --}}
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate py-3">
                                    {{ $verzoek->user->name }}
                                </p>
                            </div>

                            {{-- Rechts/Onder: Knoppen (op mobiel elk 50% breed via flex-1) --}}
                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <!-- Goedkeuren -->
                                <form action="{{ route('rides.passengers.update', [$ride->id, $verzoek->id]) }}" method="POST" class="flex-1 sm:flex-none">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ \App\Enums\PassengerStatus::APPROVED->value }}">
                                    <button type="submit" title="Goedkeuren" class="w-full flex justify-center items-center p-2 text-green-600 bg-green-50 border-2 border-green-200 rounded-xl hover:bg-green-100 transition shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </form>

                                <!-- Afwijzen -->
                                <form action="{{ route('rides.passengers.update', [$ride->id, $verzoek->id]) }}" method="POST" class="flex-1 sm:flex-none">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ \App\Enums\PassengerStatus::REJECTED->value }}">
                                    <button type="submit" title="Afwijzen" class="w-full flex justify-center items-center p-2 text-red-600 bg-red-50 border-2 border-red-200 rounded-xl hover:bg-red-100 transition shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
</x-layout>