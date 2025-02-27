<?php

namespace PhpTelegramBot\Laravel\Telegram\Commands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;
use PhpTelegramBot\Laravel\Facades\Telegram;
use PhpTelegramBot\Laravel\Telegram\Conversation\ConversationWrapper;
use PhpTelegramBot\Laravel\Telegram\UsesEffectiveEntities;

class GenericmessageCommand extends SystemCommand
{
    use UsesEffectiveEntities;

    protected $name = 'genericmessage';
    protected $description = 'Handles Genericmessages';
    protected $version = '1.0';

    public function execute(): ServerResponse
    {
        $return = Telegram::call($this->getUpdate());
        if ($return instanceof ServerResponse) {
            return $return;
        }

        $user = $this->getEffectiveUser($this->getUpdate());
        $chat = $this->getEffectiveChat($this->getUpdate());

        // Check Conversation
        $conversation = new Conversation(
            user_id: $user->getId(),
            chat_id: $chat->getId()
        );

        if ($conversation->exists() && ($command = $conversation->getCommand())) {
            return $this->getTelegram()->executeCommand($command);
        }

        return Request::emptyResponse();
    }

}