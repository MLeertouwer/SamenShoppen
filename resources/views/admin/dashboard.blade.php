<x-layout>
  <x-slot name="header">
    <h1 class="text-xl font-semibold leading-none text-heading text-center py-5">Welkom, {{ Auth::user()->name }}</h1>
   </x-slot>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

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

</div>

</x-layout>