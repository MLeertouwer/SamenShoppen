<x-layout>
    <x-slot name="header"></x-slot>

    <div class="max-w-6xl mx-auto px-4 py-6">

        <!-- Header sectie -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-5 mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Ritten</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Bekijk de beschikbare ritten en meld je aan om mee te rijden, of maak zelf een rit aan.
                </p>
            </div>
            <!-- Rit melden -->
            <div>
                <a href="{{ route('ritten.create') }}" class="inline-flex items-center justify-center bg-[#0d0e1c] text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:bg-opacity-90 transition-all space-x-2 w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Rit aanmaken</span>
                </a>
            </div>
        </div>

        @php
        $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
        $daysInMonth = $startOfMonth->daysInMonth;
        $blankDays = $startOfMonth->isoWeekday() - 1;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- De Kalender -->
            <div class="lg:col-span-5 w-full max-w-md mx-auto lg:mx-0">
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">

                    <div class="bg-blue-600 px-4 py-4 flex justify-between items-center text-white font-bold">
                        <span class="text-lg tracking-wide capitalize">{{ $startOfMonth->locale('nl')->isoFormat('MMMM') }}</span>
                        <span class="text-lg tracking-wide">{{ $year }}</span>
                    </div>

                    <div class="grid grid-cols-7 gap-1 p-4 text-center text-xs font-bold text-gray-400 border-b">
                        @foreach(\Carbon\CarbonPeriod::create(\Carbon\Carbon::now()->startOfWeek(), 7) as $date)
                        <div class="capitalize">
                            {{ trim($date->locale('nl')->isoFormat('ddd'), '.') }}
                        </div>
                        @endforeach
                    </div>

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
                                   {{ $isSelected ? 'bg-indigo-900 text-white shadow-md' : ($isToday ? 'bg-blue-600 text-white shadow-sm' : 'bg-blue-50/50') }}">

                            <span class="font-bold text-base {{ $isSelected || $isToday ? 'text-white' : 'text-blue-600' }}">
                                {{ $day }}
                            </span>

                            <span class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 {{ $isSelected || $isToday ? 'bg-white' : 'bg-red-600' }} rounded-full"></span>
                        </a>
                        @else
                        <div class="relative flex items-center justify-center h-10 w-10 mx-auto rounded-full 
                                    {{ $isToday ? 'bg-blue-600 text-white shadow-sm' : '' }}">
                            <span class="{{ $isToday ? 'text-white font-bold' : 'text-gray-700' }}">
                                {{ $day }}
                            </span>
                        </div>
                        @endif
                        @endfor
                </div>
            </div>
        </div>

        <!-- RECHTER KOLOM: Rittenlijst (Foto 1 Stijl) -->
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
                <!-- DYNAMISCHE ROUTE: Linkt nu naar ritten.show met het ID -->
                <a href="{{ route('ritten.show', $ride->id) }}" class="block bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:border-blue-300 transition-all relative group">

                    <div class="flex justify-between items-start gap-4 w-full">
                        <!-- Bestemming & Adres -->
                        <div class="flex items-start space-x-2.5 text-blue-600">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg group-hover:text-blue-600 transition-colors leading-tight">
                                    {{ $ride->destination_store }}
                                </h3>
                                <p class="text-xs font-semibold text-gray-500 mt-1">
                                    {{ $ride->destination_address }}
                                </p>
                            </div>
                        </div>

                        <!-- Plekken over badge -->
                        @php
                        $plekkenOver = $ride->max_passengers - $ride->passengers->count();
                        $isPast = \Carbon\Carbon::parse($ride->departure_time)->isPast();
                        @endphp

                        <div class="flex flex-row items-end gap-1.5 flex-shrink-0">

                            <!-- Status badge -->
                            @if($isPast)
                            <!-- 3. Rit is geweest -->
                            <span class="bg-gray-100 text-gray-600 border border-gray-300 text-[11px] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap">
                                Afgelopen
                            </span>
                            @elseif($plekkenOver <= 0)
                                <!-- 2. Geen plek meer -->
                                <span class="bg-red-50 text-red-600 border border-red-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap">
                                    Volgeboekt
                                </span>
                                @else
                                <!-- 1. Nog plek open -->
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-[11px] font-bold px-2.5 py-0.5 rounded-full whitespace-nowrap">
                                    Open
                                </span>
                                @endif

                                <!-- Plekken over badge -->
                                <span class="bg-black text-white text-[11px] font-bold px-2.5 py-1 rounded-full whitespace-nowrap">
                                    {{ $plekkenOver }} {{ $plekkenOver == 1 ? 'plek' : 'plekken' }} over
                                </span>
                        </div>
                    </div>

                    <!-- Details Rij -->
                    <div class="flex flex-col flex-wrap gap-2 text-xs font-semibold text-gray-500 ml-7 pt-3 border-t border-gray-50">

                        <!-- Bestuurder -->
                        <p class="text-sm text-gray-500">
                            Aangemaakt door: <span class="text-gray-700 font-medium">{{ $ride->driver->user->name ?? 'undefined' }}</span>
                        </p>

                        <div class="flex flex-row gap-2">
                            <!-- Tijd -->
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($ride->departure_time)->format('H:i') }} uur</span>
                            </div>
                            <!-- Passagiers -->
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>{{ $ride->passengers->count() }} {{ $ride->passengers->count() == 1 ? 'passagier' : 'passagiers' }}</span>
                            </div>
                        </div>
                    </div>

                </a>
                @endforeach
            </div>
            @endif
            @else
            <!-- Lege status -->
            <div class="bg-gray-50/60 border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center h-full flex flex-col items-center justify-center min-h-[300px]">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 3V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm font-medium text-gray-500">Klik op een datum in de kalender met een stipje om de beschikbare ritten te bekijken.</p>
            </div>
            @endif
        </div>

    </div>
    </div>
</x-layout>