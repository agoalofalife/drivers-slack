<?php
declare(strict_types=1);

namespace Tests\Unit;
use FondBot\Drivers\Slack\SlackTemplateCompiler;
use FondBot\Drivers\Slack\Templates\RequestButton;
use FondBot\Drivers\Slack\Templates\RequestConfirmButton;
use FondBot\Templates\Keyboard;
use FondBot\Templates\Keyboard\PayloadButton;
use FondBot\Templates\Keyboard\ReplyButton;
use FondBot\Templates\Keyboard\UrlButton;
use Tests\TestCase;


class SlackTemplateCompilerTest extends TestCase
{
    /**
     * @var SlackTemplateCompiler
     */
    protected $slackTemplateCompiler;

    public function setUp()
    {
        $this->slackTemplateCompiler = new SlackTemplateCompiler();
    }

    public function test_compilePayloadButton()
    {
        $payloadButton = $this->mock(PayloadButton::class);
        $this->assertEquals([], $this->slackTemplateCompiler->compilePayloadButton($payloadButton, []));
    }

    public function test_compileReplyButton()
    {
        $replyButton = $this->mock(ReplyButton::class);
        $this->assertEquals([], $this->slackTemplateCompiler->compileReplyButton($replyButton, []));
    }

    public function test_compileUrlButton()
    {
        $urlButton = $this->mock(UrlButton::class);
        $this->assertEquals([], $this->slackTemplateCompiler->compileUrlButton($urlButton, []));
    }

    public function test_compileKeyboard()
    {
        $keyboard = (new Keyboard)
            ->addButton(
                (new RequestButton())->setLabel('Телефон')->setActivator('recommend')->setConfirm((new RequestConfirmButton()))
            )
            ->addButton(
                (new RequestButton())->setLabel('Чашка')
            )
            ->addButton(
                (new RequestButton())->setLabel('Ложка')
            );

        $this->slackTemplateCompiler->compile($keyboard);
    }

    public function test_compileKeyboard_not_buttons()
    {
        $keyboard = (new Keyboard);

        $this->slackTemplateCompiler->compile($keyboard);
    }
}