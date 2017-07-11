<?php

declare(strict_types=1);

namespace FondBot\Drivers\Slack;

use FondBot\Drivers\Chat;
use FondBot\Drivers\Extensions\WebhookVerification;
use FondBot\Drivers\User;
use FondBot\Drivers\Driver;
use FondBot\Drivers\CommandHandler;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Drivers\TemplateCompiler;
use FondBot\Drivers\Exceptions\InvalidRequest;

class SlackDriver extends Driver implements WebhookVerification
{
    use FactoryTypeRequest;

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return  'https://slack.com/api/';
    }

    /**
     * Verify incoming request data.
     *
     */
    public function verifyRequest(): void
    {
        if ( !$this->request->getParameter('token') == $this->getParameter('verify_token')
            &&
            !( $this->request->hasParameters(['type', 'event.user', 'event.text', 'event.channel'])
            ||
            $this->request->hasParameters(['channel_id', 'text', 'user_id']))
           )
        {
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
        $from     = $this->getUserId($this->request);

        $userData = $this->http->get($this->getBaseUrl() . $this->mapDriver('infoAboutUser'),
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

        $name = [$responseUser->user->profile->first_name?? null, $responseUser->user->profile->last_name ?? null];
        $name = implode(' ', $name);
        $name = trim($name);

        return new User(
            (string) $responseUser->user->id,
            $name,
            $responseUser->user->name   ?? null
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
            $this->http,
            $this->request->getParameters()
        );
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
    public function mapDriver(string $name) : string
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
        return new SlackTemplateCompiler();
    }

    /**
     * Get command handler instance.
     *
     * @return CommandHandler
     */
    public function getCommandHandler(): CommandHandler
    {
        return new SlackCommandHandler($this);
    }

    /**
     * Get current chat.
     *
     * @return Chat
     */
    public function getChat(): Chat
    {
        return new Chat(
            (string) $this->getChatId($this->request),
            ''
        );
    }

    /**
     * Whether current request type is verification.
     *
     * @return bool
     */
    public function isVerificationRequest(): bool
    {
        return $this->request->getParameter('type') === 'url_verification';
    }

    /**
     * Run webhook verification and respond if required.
     * @return mixed
     * @throws InvalidRequest
     */
    public function verifyWebhook()
    {
        if ($this->request->getParameter('token') === $this->getParameter('verify_token')) {
            return $this->request->getParameter('challenge');
        }
        throw new InvalidRequest('Invalid verify token');
    }
}