<?php

namespace App\Console\Commands;

use App\Events\ChatMessage;
use Illuminate\Console\Command;

class SendChatMessage extends Command
{
    protected $signature = 'chat:send {message?} {--sender=CLI}';

    protected $description = 'Send a chat message via Reverb for testing';

    public function handle(): void
    {
        $message = $this->argument('message') ?? $this->ask('Enter message');
        $sender = $this->option('sender');

        ChatMessage::dispatch($message, $sender, now()->format('H:i:s'));

        $this->info("Message sent: [{$sender}] {$message}");
    }
}
