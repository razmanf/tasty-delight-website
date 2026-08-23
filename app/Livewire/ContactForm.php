<?php

namespace App\Livewire;

use Livewire\Component;

class ContactForm extends Component
{
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    public $successMessage = '';

    protected $rules = [
        'first_name' => 'required|min:2',
        'last_name' => 'required|min:2',
        'email' => 'required|email',
        'subject' => 'required|min:3',
        'message' => 'required|min:10',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        $this->validate();

        $contactMessage = \App\Models\ContactMessage::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        \Illuminate\Support\Facades\Mail::to('support@tastydelight.shop')->queue(new \App\Mail\ContactMessageNotification($contactMessage));

        $this->successMessage = 'Thank you! Your message has been sent successfully. Our team will get back to you shortly.';
        
        $this->reset(['first_name', 'last_name', 'email', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
