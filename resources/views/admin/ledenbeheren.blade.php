<x-layout>
   <x-slot name="header">
    <h1 class="text-xl font-semibold leading-none text-heading text-center py-5">Leden beheren</h1>
   </x-slot>

    <div class="flow-root">
    <ul role="list" class="divide-y divide-default">
        @forelse ($members as $member)
            <li class="py-4 sm:py-4">
                <div class="flex items-center justify-between gap-4">
                    
                    <!-- 1. Gebruikersgegevens -->
                    <div class="flex-1 min-w-0 ms-2">
                        <p class="font-medium text-heading truncate">
                            {{ $member->name }}
                        </p>
                        <p class="text-sm text-body truncate">
                            {{ $member->email }}
                        </p>
                    </div>

                    <!-- 2. Status Badge -->
                    <div>
                        @if ($member->status === 'goedgekeurd')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $member->status }}
                            </span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $member->status }}
                            </span>
                        @endif
                    </div>

                    <!-- 3. Acties (Bekijken & Toekomstige Verwijderknop) -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.members.show', $member->id) }}" 
                           class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Bekijken
                        </a>
                        
                        <!-- Hier komt straks jouw DELETE-formulier zodra de controller klaar is! -->
                        <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-lg transition-colors">
                                Verwijderen
                            </button>
                        </form>
                    </div>

                </div>
            </li>
        @empty
            <li class="py-4 sm:py-4">
                <p class="text-sm text-body">Er zijn momenteel geen leden aanwezig.</p>
            </li>
        @endforelse
    </ul>
</div>
</x-layout>