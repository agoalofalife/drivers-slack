<?php

declare(strict_types=1);

namespace FondBot\Drivers\Telegram;

use FondBot\Drivers\Chat;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Drivers\CommandHandler;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Drivers\TemplateCompiler;
use GuzzleHttp\Client;
use FondBot\Drivers\Exceptions\InvalidRequest;

class SlackDriver extends Driver
{
    private $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Configuration parameters.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'token'
        ];
    }

    /**
     * Verify incoming request data.
     *
     */
    public function verifyRequest(): void
    {
        if (
            !$this->hasRequest('type')  ||
            !$this->hasRequest('user')  ||
            !$this->hasRequest('text')  ||
            $this->getRequest('type') !== 'message'
        ) {
            throw new InvalidRequest('Invalid payload');
        }
    }


    /**
     * Get user.
     *
     * @return User
     * @throws \Exception
     */
    public function getUser(): User
    {
        $from     = $this->getRequest('user');
        $userData = $this->guzzle->get($this->getBaseUrl() . $this->mapDriver('infoAboutUser'),
            [
                'query' => [
                    'token' => $this->getParameter('token'),
                    'user'  => $from
                ]
            ])->getBody();


        if ( ($responseUser = $this->jsonNormalize($userData))->ok === false)
        {
            throw new \Exception($responseUser->error);
        }
        $user['user']['id'] = $responseUser->user->id;
        $user['user']['profile']['first_name']  =   $responseUser->user->profile->first_name;
        $user['user']['profile']['last_name']   =   $responseUser->user->profile->last_name;
        $user['user']['name']                   =   $responseUser->user->name;

        $name = [$from['first_name'] ?? null, $from['last_name'] ?? null];
        $name = implode(' ', $name);
        $name = trim($name);

        return new User(
            (string) $user['user']['id'],
            $name,
            $user['user']['name']   ?? null
        );
    }

    /**
     * Get message received from sender.
     *
     * @return ReceivedMessage
     */
    public function getMessage(): ReceivedMessage
    {
        return new SlackReceivedMessage(
            $this->guzzle,
            $this->getParameter('token'),
            $this->getRequest());
    }

    /**
     *  Send reply to participant.
     *
     * @param User $sender
     * @param string $text
     * @param Keyboard|null $keyboard
     * @return OutgoingMessage|ReceiverMessage
     * @internal param Receiver $receiver
     */
    public function sendMessage(User $sender, string $text, Keyboard $keyboard = null): OutgoingMessage
    {
        $message = new SlackOutgoingMessage($sender, $text, $keyboard);
        $query   = array_merge($message->toArray(), [
            'token'   => $this->getParameter('token')
        ]);

        try {
            $this->guzzle->post($this->getBaseUrl() . $this->mapDriver('postMessage'), [
                'form_params' => $query
            ]);
        } catch (RequestException $exception) {
            $this->error(get_class($exception), [$exception->getMessage()]);
        }

        return $message;
    }


    private function getBaseUrl(): string
    {
        return  'https://slack.com/api/';
    }

    /**
     * Getting json conversion from guzzle
     *
     * @param $guzzleBody
     * @return mixed
     */
    private function jsonNormalize($guzzleBody)
    {
        return json_decode((string) $guzzleBody);
    }

    /**
     * The array method for correct job slack driver
     *
     * @param string $name
     * @return string
     * @throws \Exception
     */
    private function mapDriver(string $name) : string
    {
        $map =  [
            'infoAboutUser' => 'users.info',
            'postMessage'   => 'chat.postMessage'
        ];

        if ( isset($map[$name]) )
        {
            return $map[$name];
        } else{
            throw new \Exception('no matches');
        }

    }

    /**
     * Get template compiler instance.
     *
     * @return TemplateCompiler|null
     */
    public function getTemplateCompiler(): ?TemplateCompiler
    {
        // TODO: Implement getTemplateCompiler() method.
    }

    /**
     * Get command handler instance.
     *
     * @return CommandHandler
     */
    public function getCommandHandler(): CommandHandler
    {
        // TODO: Implement getCommandHandler() method.
    }

    /**
     * Get current chat.
     *
     * @return Chat
     */
    public function getChat(): Chat
    {
        // TODO: Implement getChat() method.
    }
}