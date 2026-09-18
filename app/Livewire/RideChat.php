<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\Ride;
use Illuminate\Support\Facades\Auth;


class RideChat extends Component
{
    public Ride $ride;
    public string $messageText = '';
    public int $formKey = 0;

    public function sendMessage()
    {
        // Validatie
        $this->validate([
            'messageText' => 'required|string|max:1000',
        ]);

        // Check of de ingelogde user een memebership heeft
        $membership = Auth::user()->membership;
        if (!$membership) {
            return;
        }

        // Sla het bericht op in de database
        Message::create([
            'ride_id' => $this->ride->id,
            'membership_id' => $membership->id,
            'message_text' => $this->messageText,
        ]);

        // Update de formKey om zeker te weten dat messageText wordt gereset.
        $this->reset('messageText');
        $this->formKey++;
    }

    public function deleteMessage($messageId)
    {
        // Check of de ingelogde gebruiker een beheerder is
        if (!auth()->user()->hasRole('beheerder')) {
            abort(403, ' Je hebt onvoldoende rechten om deze actie uit te voeren.');
        }

        // Haal het bericht op en verwijder het bericht.
        $message = Message::find($messageId);

        if ($message) {
            $message->delete();
        }
    }

    public function render()
    {
        return view('livewire.ride-chat', [
            'messages' => Message::where('ride_id', $this->ride->id)->with('membership.user')->oldest()->get()
        ])->layout('components.layout');
    }
}
