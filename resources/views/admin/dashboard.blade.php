<x-layout>
  <x-slot name="header">
    <h1 class="text-xl font-semibold leading-none text-heading text-center py-5">Welkom, {{ Auth::user()->name }}</h1>
   </x-slot>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

    <div class="w-full p-6 bg-neutral-primary-soft border border-default rounded-base shadow-xs mt-6">
    <div class="flex flex-col sm:flex-row items-center justify-between mb-4 gap-4">
        <h5 class="text-xl font-semibold leading-none text-heading">
            Ritten Overzicht ({{ $rides->count() }})
        </h5>
        
        <!-- 🎛️ Filter Knoppen voor Vandaag & Morgen -->
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard', ['ride_date' => 'today']) }}" class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700 hover:bg-gray-300 {{ request('ride_date', 'today') === 'today' ? 'font-bold bg-gray-300' : '' }}">
                Vandaag
            </a>
            <a href="{{ route('admin.dashboard', ['ride_date' => 'tomorrow']) }}" class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700 hover:bg-gray-300 {{ request('ride_date') === 'tomorrow' ? 'font-bold bg-gray-300' : '' }}">
                Morgen
            </a>
        </div>
    </div>

    <div class="flow-root">
        <ul role="list" class="divide-y divide-default">
            @forelse ($rides as $ride)
                <li class="py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-heading">
                                Bestuurder: {{ $ride->driver?->user?->name ?? 'Onbekend' }}
                            </p>
                            <p class="text-sm text-body">
                                Vertrektijd: {{ \Carbon\Carbon::parse($ride->departure_time)->format('H:i') }}
                            </p>
                        </div>
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            Passagiers: {{ $ride->passengers->count() }}
                        </span>
                    </div>
                </li>
            @empty
                <li class="py-4">
                    <p class="text-sm text-body">Er zijn geen ritten gevonden voor deze dag.</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>

    <div class="w-full p-6 bg-neutral-primary-soft border border-default rounded-base shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-xl font-semibold leading-none text-heading">Laatste aanvragen</h5>
            <a href="{{ route('aanvragen') }}" class="font-medium text-fg-brand hover:underline">bekijk alle</a>
        </div>
        <div class="flow-root">
            <ul role="list" class="divide-y divide-default">
@forelse ($accountrequest as $membership)
    <li class="py-4 sm:py-4">
        <div class="flex items-center gap-2">
            <div class="flex-1 min-w-0 ms-2">
                <p class="font-medium text-heading truncate">
                    {{ $membership->user?->name }}
                </p>
                <p class="text-sm text-body truncate">
                    {{ $membership->user?->email }}
                </p>
            </div>
            @if ($membership->status === 'pending')
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ $membership->status }}
                </span>
            @elseif ($membership->status === 'active')
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ $membership->status }}
                </span>
            @elseif ($membership->status === 'rejected')
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ $membership->status }}
                </span>
            @else
                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ $membership->status }}
                </span>
            @endif
        </div>
    </li>
@empty
    <li class="py-4 sm:py-4">
        <p class="text-sm text-body">Er zijn geen openstaande aanvragen.</p>
    </li>
@endforelse
            </ul>
        </div>
    </div>

    <div class="w-full p-6 bg-neutral-primary-soft border border-default rounded-base shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-xl font-semibold leading-none text-heading">Nieuwe leden</h5>
            <a href="{{ route('ledenbeheren') }}" class="font-medium text-fg-brand hover:underline">leden beheren</a>
        </div>
        <div class="flow-root">
            <ul role="list" class="divide-y divide-default">
                @forelse ($processedRequests as $membership)
    <li class="py-4 sm:py-4">
        <div class="flex items-center gap-2">
            <div class="flex-1 min-w-0 ms-2">
                <p class="font-medium text-heading truncate">
                    {{ $membership->user?->name }}
                </p>
                <p class="text-sm text-body truncate">
                    {{ $membership->user?->email }}
                </p>
            </div>
            <div class="inline-flex items-center font-medium text-heading">
                @if ($membership->status === 'active')
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                        {{ $membership->status }}
                    </span>
                @elseif ($membership->status === 'rejected')
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">
                        {{ $membership->status }}
                    </span>
                @endif
            </div>
        </div>
    </li>
                @empty
                    <li class="py-4 sm:py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 min-w-0 ms-2">
                                <p class="font-medium text-heading truncate">
                                    Er zijn nog geen verwerkte aanvragen.
                                </p>
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Klachten & Suggesties Sectie -->
    <div class="col-span-1 md:col-span-2 w-full p-6 bg-neutral-primary-soft border border-default rounded-base shadow-xs mt-6">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-4 gap-4">
            <h5 class="text-xl font-semibold leading-none text-heading">Klachten & Suggesties</h5>
            
            <!-- Filter Knoppen -->
            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700 hover:bg-gray-300 {{ request('type') ? '' : 'font-bold bg-gray-300' }}">
                    Alles
                </a>
                <a href="{{ route('admin.dashboard', ['type' => 'suggestie']) }}" class="px-3 py-1 text-sm rounded bg-blue-100 text-blue-800 hover:bg-blue-200 {{ request('type') === 'suggestie' ? 'font-bold ring-2 ring-blue-500' : '' }}">
                    Suggesties
                </a>
                <a href="{{ route('admin.dashboard', ['type' => 'klacht']) }}" class="px-3 py-1 text-sm rounded bg-red-100 text-red-800 hover:bg-red-200 {{ request('type') === 'klacht' ? 'font-bold ring-2 ring-red-500' : '' }}">
                    Klachten
                </a>
            </div>
        </div>

        <div class="flow-root">
            <ul role="list" class="divide-y divide-default">
                @forelse ($suggestions ?? [] as $suggestion)
                    <li class="py-4">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-heading">
                                    {{ $suggestion->user?->name ?? 'Onbekende gebruiker' }} 
                                    <span class="text-xs text-body">({{ $suggestion->user?->email }})</span>
                                </p>
                                <p class="text-sm text-body mt-1">
                                    {{ $suggestion->bericht }}
                                </p>
                            </div>
                            <div>
                                @if ($suggestion->type === 'klacht')
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded uppercase">
                                        {{ $suggestion->type }}
                                    </span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded uppercase">
                                        {{ $suggestion->type }}
                                    </span>
                                @endif

                                <!-- Afhandelen / Verwijderen Knop -->
                                <form action="{{ route('admin.suggestions.destroy', $suggestion->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit wilt afhandelen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-gray-900 text-white hover:bg-black rounded text-xs font-bold transition mt-3">
                                        Afhandelen
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-4">
                        <p class="text-sm text-body">Er zijn geen klachten of suggesties gevonden.</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

</div>

</x-layout>