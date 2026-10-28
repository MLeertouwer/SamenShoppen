<x-layout>
   <x-slot name="header">
    <h1 class="text-xl font-semibold leading-none text-heading text-center py-5">Lid Details</h1>
   </x-slot>

    <div class="max-w-4xl mx-auto p-6 bg-neutral-primary-soft border border-default rounded-base shadow-xs mt-6">
        <div class="space-y-4">
            
            <div>
                <label class="text-sm font-medium text-body">Naam:</label>
                <p class="text-lg font-semibold text-heading">{{ $member->name }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-body">E-mailadres:</label>
                <p class="text-text-main">{{ $member->email }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-body">Status:</label>
                <p class="text-text-main">{{ $member->membership?->status ?? 'Onbekend' }}</p>
            </div>

        </div>
    </div>
</x-layout>