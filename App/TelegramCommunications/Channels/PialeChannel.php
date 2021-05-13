<?php


namespace Bot\App\TelegramCommunications\Channels;


use Bot\Views\PialeChannelView;
use BotFramework\App\TelegramCommunications\Channels\Channel;

class PialeChannel extends Channel
{
	protected static $channelID = '@PialeChannel';
	protected static $defaultView = PialeChannelView::class;
}