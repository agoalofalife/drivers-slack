<?php
declare(strict_types=1);

namespace FondBot\Drivers\Slack;

use FondBot\Drivers\Slack\Templates\RequestSelect;
use FondBot\Templates\Keyboard;
use FondBot\Drivers\TemplateCompiler;
use FondBot\Templates\Keyboard\Button;
use FondBot\Templates\Keyboard\UrlButton;
use FondBot\Templates\Keyboard\ReplyButton;
use FondBot\Templates\Keyboard\PayloadButton;

/**
 * Class SlackTemplateCompiler
 *
 * @package FondBot\Drivers\Slack
 */
class SlackTemplateCompiler extends TemplateCompiler
{
    /**
     * @var array
     */
    private $keyboardButtons = [
        'RequestButton'
    ];


    /**
     * Compile keyboard.
     *
     * @param Keyboard $keyboard
     * @param array $args
     *
     * @return mixed
     */
    protected function compileKeyboard(Keyboard $keyboard, array $args): ?array
    {
        $buttons = collect($keyboard->getButtons())
            ->filter(function (Button $button) use ($keyboard) {
                return in_array($button->getName(), $this->keyboardButtons, true);
            })
            ->map(function (Button $button) {
                return $this->compile($button);
            })
            ->toArray();

        if (!count($buttons))
        {
            return null;
        }
        $new['attachments'] = [];
        foreach ($buttons as $button){
            $new['attachments'] = array_merge($new['attachments'], json_decode($button['attachments']));
        }
        $new['attachments'] = json_encode($new['attachments']);
//        file_put_contents(path().'file.txt', $buttons[0]['attachments']);
        file_put_contents(path().'file.txt', json_encode($new));
        return $new;
    }

    /**
     * @param RequestSelect $requestSelect
     * @param array         $args
     * @return array|null
     */
    protected function compileRequestSelect(RequestSelect $requestSelect, array $args) : ?array
    {
        file_put_contents(path(). 'file.txt', 'I am inner');
    }
    /**
     * Compile payload button.
     *
     * @param PayloadButton $button
     * @param array $args
     *
     * @return array
     */
    public function compilePayloadButton(PayloadButton $button, array $args): array
    {
        return [];
    }

    /**
     * Compile reply button.
     *
     * @param ReplyButton $button
     * @param array $args
     *
     * @return array
     */
    public function compileReplyButton(ReplyButton $button, array $args): array
    {
        return [];
    }

    /**
     * Compile url button.
     *
     * @param UrlButton $button
     * @param array $args
     *
     * @return array
     */
    public function compileUrlButton(UrlButton $button, array $args): array
    {
        return [];
    }
}
