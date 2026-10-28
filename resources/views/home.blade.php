<x-layout>
 <x-slot name="header">
    <img class=" max-w md:max-w-full md:w-full max-h-[340px] md:max-h-[240px] object-cover object-[center_80%]" src="{{ asset('images/homepage-image.jpg') }}" alt="Samen Shoppen">
  </x-slot>
    <h1 class="text-2xl font-bold text-red-600 font-serif">Welkom, {{ Auth::user()->name }}</h1>

<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-x-auto mt-6">
    <h2 class="text-lg font-semibold text-gray-800 px-6 py-4 border-b">Ontvangen betaalverzoeken</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Afzender</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bedrag</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Omschrijving</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($receivedRequests as $request)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $request->sender?->name ?? 'Undefined' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        € {{ number_format($request->amount, 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $request->description }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($request->status === 'pending')
                            <a href="{{ $request->payment_request_url }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition duration-150 ease-in-out">
                                Betaal Tikkie
                            </a>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Betaald
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                        Geen ontvangen betaalverzoeken
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>



</x-layout>