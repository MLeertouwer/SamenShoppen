<x-layout>
<x-slot name="header">
    <h1 class="text-xl font-semibold leading-none text-heading text-center py-5">Alle aanvragen</h1>
   </x-slot>

    <div class="flow-root">
        @if(session('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded max-w-md mx-auto mt-6">
              {{ session('success') }}
          </div>
        @endif 

        <ul role="list" class="divide-y divide-default">
            @forelse ($accountrequest as $membership)
            <li class="py-4 sm:py-4">
                <div class="flex items-center gap-2">
                    <div class="flex-1 min-w-0 ms-2">
                        <p class="font-medium text-heading truncate">
                            {{ $membership->user->name }}
                        </p>
                        <p class="text-sm text-body truncate">
                            {{ $membership->user->email }}
                        </p>
                    </div>
                    <div class="inline-flex items-center font-medium text-heading">
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
                </div>
              <div class="mt-4 flex gap-2 ms-2">
      
        <form action="{{ route('user.approve', $membership->user->id) }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700">
                Goedkeuren
            </button>
        </form>

        <form action="{{ route('user.reject', $membership->user->id) }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
                Afkeuren
            </button>
        </form>
    </div>
            </li>
            @empty
                <li class="py-4 sm:py-4">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 min-w-0 ms-2">
                            <p class="font-medium text-heading truncate">
                                Er zijn geen aanvragen op dit moment.
                            </p>
                        </div>
                    </div>
                </li>
            @endforelse
        </ul>
   </div>
</x-layout>