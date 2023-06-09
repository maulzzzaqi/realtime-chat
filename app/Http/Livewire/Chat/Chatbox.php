<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class Chatbox extends Component
{
    public $selectedConversation;
    public $receiverInstance;
    public $messageCount;
    public $messages;
    public $paginateVar = 10;
    public $listeners = ['loadConversation', 'pushMessage'];

    public function pushMessage($messageId){
        $newMessage =  Message::find($messageId);
        $this->messages->push($newMessage);
    }

    public function loadConversation(Conversation $conversation, User $receiver){
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;

        $this->messageCount = Message::where('conversation_id', $this->selectedConversation->id)->count();
        $this->messages = Message::where('conversation_id', $this->selectedConversation->id)
        ->skip($this->messageCount - $this->paginateVar)
        ->take($this->paginateVar)->get();

        $this->dispatchBrowserEvent('chatSelected');
    }

    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
