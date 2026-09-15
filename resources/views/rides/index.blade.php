<x-layout>
    <x-slot name="header"></x-slot>

    <div class="max-w-6xl mx-auto px-4 py-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-5 mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Ritten</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Bekijk de beschikbare ritten en meld je aan om mee te rijden, of maak zelf een rit aan.
                </p>
            </div>
            <!-- Rit melden -->
            <div class="relative w-full sm:w-auto" x-data="{ open: false }">
                <!-- De hoofdknop -->
                <button @click="open = !open" type="button" class="w-full sm:w-auto inline-flex items-center justify-center bg-[#0d0e1c] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:bg-opacity-90 transition-all space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Rit aanmaken</span>
                    <svg class="w-3.5 h-3.5 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Het uitklapmenu -->
                <div x-show="open"
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                    class="absolute left-0 sm:left-auto right-0 mt-2 w-full sm:w-64 bg-[#0d0e1c] rounded-xl shadow-lg border border-gray-800 py-1.5 z-50"
                    style="display: none;">

                    <!-- Optie 1: Passagiersrit -->
                    <a href="{{ route('ritten.create') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800/60 transition">
                        <span class="p-1.5 bg-orange-950 text-orange-400 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </span>
                        <div>
                            <span class="block text-white text-sm font-bold">Passagiersrit</span>
                            <span class="block text-xs font-normal text-gray-400">Samen boodschappen doen</span>
                        </div>
                    </a>

                    <!-- Optie 2: Boodschappenrit -->
                    <a href="{{ route('ritten.create', ['type' => 'boodschappen']) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-800/60 transition border-t border-gray-800">
                        <span class="p-1.5 bg-emerald-950 text-emerald-400 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                            </svg>
                        </span>
                        <div>
                            <span class="block text-white text-sm font-bold">Boodschappenrit</span>
                            <span class="block text-xs font-normal text-gray-400">Boodschappen laten bezorgen</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        @php
        $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
        $daysInMonth = $startOfMonth->daysInMonth;
        $blankDays = $startOfMonth->isoWeekday() - 1;

        // Navigatie datums voor de vorige en volgende maand
        $prevMonth = $startOfMonth->copy()->subMonth();
        $nextMonth = $startOfMonth->copy()->addMonth();
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- De Kalender -->
            <div class="lg:col-span-5 w-full max-w-md mx-auto lg:mx-0">
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">

                    <!-- Maand Header met Navigatie -->
                    <div class="bg-orange-600 px-4 py-4 flex justify-between items-center text-white font-bold">
                        <a href="?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}" class="p-1 rounded-lg hover:bg-orange-700 transition-colors" title="Vorige maand">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>

                        <span class="text-lg tracking-wide capitalize">
                            {{ $startOfMonth->locale('nl')->isoFormat('MMMM YYYY') }}
                        </span>

                        <a href="?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}" class="p-1 rounded-lg hover:bg-orange-700 transition-colors" title="Volgende maand">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <!-- Dagen van de week -->
                    <div class="grid grid-cols-7 gap-1 p-4 text-center text-xs font-bold text-gray-400 border-b">
                        @foreach(\Carbon\CarbonPeriod::create(\Carbon\Carbon::now()->startOfWeek(), 7) as $date)
                        <div class="capitalize">
                            {{ trim($date->locale('nl')->isoFormat('ddd'), '.') }}
                        </div>
                        @endforeach
                    </div>

                    <!-- Dagen van de maand -->
                    <div class="grid grid-cols-7 gap-y-3 gap-x-2 p-4 text-center text-sm font-medium">
                        @for ($i = 0; $i < $blankDays; $i++)
                            <div>
                    </div>
                    @endfor

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                        $loopDate=\Carbon\Carbon::create($year, $month, $day);
                        $currentDateString=$loopDate->format('Y-m-d');
                        $hasRides = isset($ridesPerDay[$currentDateString]);
                        $isToday = $loopDate->isToday();
                        $isSelected = ($selectedDate === $currentDateString);
                        @endphp

                        @if($hasRides)
                        <a href="?month={{ $month }}&year={{ $year }}&selected_date={{ $currentDateString }}"
                            class="relative flex items-center justify-center h-10 w-10 mx-auto rounded-full transition-all hover:scale-110 cursor-pointer
                                          {{ $isSelected ? 'bg-indigo-900 text-white shadow-md' : ($isToday ? 'bg-orange-600 text-white shadow-sm' : 'bg-orange-50/80') }}">

                            <span class="font-bold text-base {{ $isSelected || $isToday ? 'text-white' : 'text-orange-600' }}">
                                {{ $day }}
                            </span>

                            <span class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 {{ $isSelected || $isToday ? 'bg-white' : 'bg-red-600' }} rounded-full"></span>
                        </a>
                        @else
                        <div class="relative flex items-center justify-center h-10 w-10 mx-auto rounded-full 
                                            {{ $isToday ? 'bg-orange-600 text-white shadow-sm' : '' }}">
                            <span class="{{ $isToday ? 'text-white font-bold' : 'text-gray-700' }}">
                                {{ $day }}
                            </span>
                        </div>
                        @endif
                        @endfor
                </div>

            </div>
        </div>

        <!-- Rittenlijst -->
        <div class="lg:col-span-7 w-full">
            @if($selectedDate)
            @php
            $selectedRides = $ridesPerDay[$selectedDate] ?? collect();
            @endphp

            <div class="mb-4">
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                    Ritten op {{ \Carbon\Carbon::parse($selectedDate)->locale('nl')->isoFormat('D MMMM YYYY') }}
                </h2>
            </div>

            @if($selectedRides->isEmpty())
            <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 shadow-sm">
                <p class="text-sm text-gray-500 italic">Er zijn helaas geen ritten gepland voor deze dag.</p>
            </div>
            @else
            <div class="space-y-4">
                @foreach($selectedRides as $ride)
                @php
                $approvedCount = $ride->approvedPassengersCount();
                $plekkenOver = max(0, $ride->max_passengers - $approvedCount);
                $statusValue = $ride->status->value ?? $ride->status;
                $isGrocery = $ride->is_grocery_only; // Check of het een boodschappenrit is
                @endphp

                <a href="{{ route('ritten.show', $ride->id) }}" class="block bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:border-orange-300 transition-all relative group">

                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 w-full">
                        <!-- Bestemming & Adres -->
                        <div class="flex items-start space-x-2.5 {{ $isGrocery ? 'text-emerald-600' : 'text-orange-600' }}">
                            @if($isGrocery)
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            @endif

                            <div>
                                <h3 class="font-bold text-gray-900 text-lg group-hover:text-orange-600 transition-colors leading-tight">
                                    {{ $ride->destination_store }}
                                </h3>
                                <p class="text-xs font-semibold text-gray-500 mt-1">
                                    {{ $ride->destination_address }}
                                </p>
                            </div>
                        </div>

                        <!-- Badges -->
                        <div class="flex flex-col items-start sm:items-end gap-1.5 flex-shrink-0 w-fit">
                            <!-- Rit Badge  -->
                            @if($isGrocery)
                            <span class="w-full text-center bg-emerald-100 text-emerald-800 border border-emerald-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap flex items-center justify-center gap-1">
                                🛒 Boodschappenrit
                            </span>
                            @else
                            <span class="w-full text-center bg-orange-100 text-orange-800 border border-orange-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap flex items-center justify-center gap-1">
                                🚗 Passagiersrit
                            </span>
                            @endif

                            <!-- Status badges -->
                            <div class="flex items-center gap-1.5 w-fit">
                                <!-- Status badge -->
                                @if($statusValue === 'verlopen' || \Carbon\Carbon::parse($ride->departure_time)->isPast())
                                <span class="bg-gray-100 text-gray-600 border border-gray-300 text-[11px] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap">
                                    Afgelopen
                                </span>
                                @elseif($statusValue === 'vol' || $plekkenOver <= 0)
                                    <span class="bg-red-50 text-red-600 border border-red-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap">
                                    Vol
                                    </span>
                                    @else
                                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap">
                                        Open
                                    </span>
                                    @endif

                                    <!-- Plekken / Lijstjes over badge -->
                                    <span class="bg-black text-white text-[11px] font-bold px-2.5 py-1 rounded-full whitespace-nowrap">
                                        {{ $plekkenOver }} {{ $isGrocery ? ($plekkenOver == 1 ? 'lijstje' : 'lijstjes') : ($plekkenOver == 1 ? 'plek' : 'plekken') }} over
                                    </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs font-semibold text-gray-500 pt-3 mt-3 border-t border-gray-50">

                        <!-- Driver -->
                        <p class="text-xs text-gray-500">
                            Aangemaakt door: <span class="text-gray-700 font-bold">{{ $ride->driver->user->name }}</span>
                        </p>

                        <div class="flex flex-row gap-4">
                            <!-- Tijd -->
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($ride->departure_time)->format('H:i') }} uur</span>
                            </div>

                            <!-- Passagiers / Boodschappenlijstjes plekken over -->
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>
                                    {{ $approvedCount }} / {{ $ride->max_passengers }} {{ $isGrocery ? ($plekkenOver == 1 ? 'Boodschappenlijst' : 'Boodschappenlijstjes') : ($plekkenOver == 1 ? 'Passagier' : 'Passagiers') }}
                                </span>
                            </div>
                        </div>
                    </div>

                </a>
                @endforeach
            </div>
            @endif
            @else
            <div class="bg-gray-50/60 border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center h-full flex flex-col items-center justify-center min-h-[300px]">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm font-medium text-gray-500">Klik op een datum in de kalender met een stipje om de beschikbare ritten te bekijken.</p>
            </div>
            @endif
        </div>

    </div>
    </div>
</x-layout>