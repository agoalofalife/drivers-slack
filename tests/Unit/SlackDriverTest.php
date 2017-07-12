<?php

declare(strict_types=1);

namespace Tests\Unit;

use FondBot\Drivers\Chat;
use FondBot\Drivers\ReceivedMessage;
use FondBot\Drivers\Slack\SlackCommandHandler;
use FondBot\Drivers\Slack\SlackDriver;
use Psr\Http\Message\MessageInterface;
use Tests\TestCase;
use GuzzleHttp\Client;
use FondBot\Helpers\Str;
use FondBot\Drivers\User;
use FondBot\Http\Request;
use FondBot\Templates\Location;
use FondBot\Templates\Attachment;
use Psr\Http\Message\ResponseInterface;
use FondBot\Drivers\Slack\SlackReceivedMessage;

/**
 * @property mixed|\Mockery\Mock|\Mockery\MockInterface $guzzle
 * @property array                                      $parameters
 * @property SlackDriver                             $driver
 */
class SlackDriverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->guzzle = $this->mock(Client::class);

        $this->driver = new SlackDriver($this->guzzle);
        $this->driver->fill($this->parameters = [], new Request($this->factoryTypeRequest(), []));
    }


    public function test_getBaseUrl()
    {
        $this->assertEquals('https://slack.com/api/', $this->driver->getBaseUrl());
    }

    public function test_verifyRequest()
    {
        $this->driver->verifyRequest();
    }

    public function test_getUser()
    {
        $this->guzzle->shouldReceive('get')
                     ->with($this->driver->getBaseUrl() . $this->driver
                     ->mapDriver('infoAboutUser'), \Mockery::type('array'))
                     ->once()->andReturnSelf();

        $this->guzzle->shouldReceive('getBody')->once()->andReturn($this->factoryUserInfo(['ok' => true]));

        $this->driver->verifyRequest();
        $this->assertInstanceOf(User::class, $this->driver->getUser());
    }

    public function test_getUser_Exception()
    {
        $error = $this->faker()->text();
        $this->guzzle->shouldReceive('get')
            ->with($this->driver->getBaseUrl() . $this->driver
                    ->mapDriver('infoAboutUser'), \Mockery::type('array'))
            ->once()->andReturnSelf();

        $this->guzzle->shouldReceive('getBody')->once()->andReturn($this->factoryUserInfo(['ok' => false, 'error' => $error]));
        $this->driver->verifyRequest();
        $this->expectException(\Exception::class);
        $this->driver->getUser();
    }

    public function test_getMessage()
    {
        $this->driver->verifyRequest();
        $this->assertInstanceOf(ReceivedMessage::class,  $this->driver->getMessage());
    }

    public function test_mapDriver()
    {
        $revise = ['infoAboutUser' => 'users.info', 'postMessage' => 'chat.postMessage'];
        $name   = $this->faker()->randomElement(['infoAboutUser', 'postMessage']);

        $this->assertEquals($revise[$name], $this->driver->mapDriver($name));
    }

    public function test_mapDriver_Exception()
    {
        $this->expectException(\Exception::class);
        $this->driver->mapDriver($this->faker()->name);
    }

    public function test_getTemplateCompiler()
    {
        $this->assertNull($this->driver->getTemplateCompiler());
    }

    public function test_getCommandHandler()
    {
        $this->assertInstanceOf(SlackCommandHandler::class, $this->driver->getCommandHandler());
    }

    public function test_getChat()
    {
        $this->driver->verifyRequest();
        $this->assertInstanceOf(Chat::class, $this->driver->getChat());
    }

    public function test_isVerificationRequest()
    {
        $this->driver->fill($this->parameters = ['token' => Str::random()], new Request(['type' => 'url_verification'], []));
        $this->assertTrue($this->driver->isVerificationRequest());
    }

    public function test_verifyWebhook()
    {
        $token = $this->faker()->uuid;
        $challenge = $this->faker()->word;

        $this->driver->fill($this->parameters = ['verify_token' => $token], new Request(['token' => $token, 'challenge' => $challenge], []));

        $this->assertEquals($challenge, $this->driver->verifyWebhook());
    }
//    /**
//     * @expectedException \FondBot\Drivers\Exceptions\InvalidRequest
//     * @expectedExceptionMessage Invalid payload
//     */
//    public function test_verifyRequest_empty_message(): void
//    {
//        $this->driver->verifyRequest();
//    }
//
//    /**
//     * @expectedException \FondBot\Drivers\Exceptions\InvalidRequest
//     * @expectedExceptionMessage Invalid payload
//     */
//    public function test_verifyRequest_no_sender(): void
//    {
//        $this->driver->fill($this->parameters, new Request(['message' => []], []));
//
//        $this->driver->verifyRequest();
//    }
//
//    public function test_verifyRequest(): void
//    {
//        $this->driver->fill(
//            $this->parameters,
//            new Request(['message' => ['from' => $this->faker()->name, 'text' => $this->faker()->word]], [])
//        );
//
//        $this->driver->verifyRequest();
//    }
//
//    public function test_getSender(): void
//    {
//        $this->driver->fill(
//            $this->parameters,
//            new Request([
//                'message' => [
//                    'from' => $response = [
//                        'id' => Str::random(),
//                        'first_name' => $this->faker()->firstName,
//                        'last_name' => $this->faker()->lastName,
//                        'username' => $this->faker()->userName,
//                    ],
//                ],
//            ], [])
//        );
//
//        $sender = $this->driver->getUser();
//        $this->assertInstanceOf(User::class, $sender);
//        $this->assertSame($response['id'], $sender->getId());
//        $this->assertSame($response['first_name'].' '.$response['last_name'], $sender->getName());
//        $this->assertSame($response['username'], $sender->getUsername());
//    }
//
//    public function test_getMessage(): void
//    {
//        $this->driver->fill(
//            $this->parameters,
//            new Request([
//                'message' => [
//                    'text' => $text = $this->faker()->text,
//                ],
//            ], [])
//        );
//
//        /** @var SlackReceivedMessage $message */
//        $message = $this->driver->getMessage();
//        $this->assertInstanceOf(SlackReceivedMessage::class, $message);
//        $this->assertSame($text, $message->getText());
//        $this->assertFalse($message->hasAttachment());
//        $this->assertNull($message->getAttachment());
//        $this->assertNull($message->getAudio());
//        $this->assertNull($message->getDocument());
//        $this->assertNull($message->getSticker());
//        $this->assertNull($message->getVideo());
//        $this->assertNull($message->getVoice());
//        $this->assertNull($message->getContact());
//        $this->assertNull($message->getLocation());
//        $this->assertNull($message->getVenue());
//    }
//
//    /**
//     * @dataProvider attachments
//     *
//     * @param string $type
//     * @param array  $result
//     */
//    public function test_getMessage_with_attachments(string $type, array $result = null): void
//    {
//        if ($result === null) {
//            $result = [
//                'file_id' => $id = $this->faker()->uuid,
//            ];
//        } else {
//            $id = collect($result)->pluck('file_id')->last();
//        }
//
//        $this->driver->fill($this->parameters, new Request(['message' => [$type => $result]], []));
//
//        // Get file path from Telegram
//        $response = $this->mock(ResponseInterface::class);
//        $response->shouldReceive('getBody')->andReturnSelf();
//        $response->shouldReceive('getContents')->andReturn(json_encode([
//            'ok' => true,
//            'result' => [
//                'file_id' => $id,
//                'file_size' => $this->faker()->randomFloat(),
//                'file_path' => $path = $this->faker()->imageUrl(),
//            ],
//        ]));
//
//        $this->guzzle->shouldReceive('post')
//            ->with(
//                'https://api.telegram.org/bot'.$this->parameters['token'].'/getFile',
//                [
//                    'form_params' => [
//                        'file_id' => $id,
//                    ],
//                ]
//            )
//            ->andReturn($response)
//            ->once();
//
//        $message = $this->driver->getMessage();
//        $this->assertInstanceOf(SlackReceivedMessage::class, $message);
//
//        $this->assertTrue($message->hasAttachment());
//
//        $attachment = $message->getAttachment();
//        $path = 'https://api.telegram.org/file/bot'.$this->parameters['token'].'/'.$path;
//
//        switch ($type) {
//            case 'photo':
//            case 'sticker':
//                $genericType = Attachment::TYPE_IMAGE;
//                break;
//            case 'document':
//                $genericType = Attachment::TYPE_FILE;
//                break;
//            case 'voice':
//                $genericType = Attachment::TYPE_AUDIO;
//                break;
//            default:
//                $genericType = $type;
//                break;
//        }
//
//        $this->assertInstanceOf(Attachment::class, $attachment);
//        $this->assertSame($genericType, $attachment->getType());
//        $this->assertSame($path, $attachment->getPath());
//    }
//
//    public function test_getMessage_with_contact_full(): void
//    {
//        $this->driver->fill(
//            $this->parameters,
//            new Request([
//                'message' => [
//                    'contact' => $contact = [
//                        'phone_number' => $phoneNumber = $this->faker()->phoneNumber,
//                        'first_name' => $firstName = $this->faker()->firstName,
//                        'last_name' => $lastName = $this->faker()->lastName,
//                        'user_id' => $userId = $this->faker()->uuid,
//                    ],
//                ],
//            ], [])
//        );
//
//        /** @var SlackReceivedMessage $message */
//        $message = $this->driver->getMessage();
//        $this->assertInstanceOf(SlackReceivedMessage::class, $message);
//        $this->assertSame($contact, $message->getContact());
//
//        $contact = $message->getContact();
//        $this->assertSame($phoneNumber, $contact['phone_number']);
//        $this->assertSame($firstName, $contact['first_name']);
//        $this->assertSame($lastName, $contact['last_name']);
//        $this->assertSame($userId, $contact['user_id']);
//    }
//
//    public function test_getMessage_with_contact_partial(): void
//    {
//        $this->driver->fill(
//            $this->parameters,
//            new Request([
//                'message' => [
//                    'contact' => $contact = [
//                        'phone_number' => $phoneNumber = $this->faker()->phoneNumber,
//                        'first_name' => $firstName = $this->faker()->firstName,
//                    ],
//                ],
//            ], [])
//        );
//
//        $contact = array_merge($contact, ['last_name' => null, 'user_id' => null]);
//
//        /** @var SlackReceivedMessage $message */
//        $message = $this->driver->getMessage();
//        $this->assertInstanceOf(SlackReceivedMessage::class, $message);
//        $this->assertSame($contact, $message->getContact());
//
//        $contact = $message->getContact();
//        $this->assertSame($phoneNumber, $contact['phone_number']);
//        $this->assertSame($firstName, $contact['first_name']);
//        $this->assertNull($contact['last_name']);
//        $this->assertNull($contact['user_id']);
//    }
//
//    public function test_getMessage_with_location(): void
//    {
//        $latitude = $this->faker()->latitude;
//        $longitude = $this->faker()->longitude;
//
//        $this->driver->fill(
//            $this->parameters,
//            new Request([
//                'message' => [
//                    'text' => $this->faker()->text,
//                    'location' => [
//                        'latitude' => $latitude,
//                        'longitude' => $longitude,
//                    ],
//                ],
//            ], [])
//        );
//
//        $message = $this->driver->getMessage();
//        $this->assertInstanceOf(SlackReceivedMessage::class, $message);
//
//        $location = $message->getLocation();
//        $this->assertInstanceOf(Location::class, $location);
//        $this->assertSame($latitude, $location->getLatitude());
//        $this->assertSame($longitude, $location->getLongitude());
//    }
//
//    public function test_getMessage_with_venue_full(): void
//    {
//        $latitude = $this->faker()->latitude;
//        $longitude = $this->faker()->longitude;
//
//        $this->driver->fill(
//            $this->parameters,
//            new Request([
//                'message' => [
//                    'text' => $this->faker()->text,
//                    'venue' => $venue = [
//                        'location' => [
//                            'latitude' => $latitude,
//                            'longitude' => $longitude,
//                        ],
//                        'title' => $title = $this->faker()->title,
//                        'address' => $address = $this->faker()->address,
//                        'foursquare_id' => $foursquareId = $this->faker()->uuid,
//                    ],
//                ],
//            ], [])
//        );
//
//        $venue['location'] = (new Location)
//            ->setLatitude($venue['location']['latitude'])
//            ->setLongitude($venue['location']['longitude']);
//
//        /** @var SlackReceivedMessage $message */
//        $message = $this->driver->getMessage();
//        $this->assertInstanceOf(SlackReceivedMessage::class, $message);
//        $this->assertEquals($venue, $message->getVenue());
//
//        $venue = $message->getVenue();
//        /** @var Location $location */
//        $location = $venue['location'];
//        $this->assertInstanceOf(Location::class, $location);
//        $this->assertSame($latitude, $location->getLatitude());
//        $this->assertSame($longitude, $location->getLongitude());
//        $this->assertSame($title, $venue['title']);
//        $this->assertSame($address, $venue['address']);
//        $this->assertSame($foursquareId, $venue['foursquare_id']);
//    }
//
//    public function test_getMessage_with_venue_partial(): void
//    {
//        $latitude = $this->faker()->latitude;
//        $longitude = $this->faker()->longitude;
//
//        $this->driver->fill(
//            $this->parameters,
//            new Request([
//                'message' => [
//                    'text' => $this->faker()->text,
//                    'venue' => $venue = [
//                        'location' => [
//                            'latitude' => $latitude,
//                            'longitude' => $longitude,
//                        ],
//                        'title' => $title = $this->faker()->title,
//                        'address' => $address = $this->faker()->address,
//                    ],
//                ],
//            ], [])
//        );
//
//        $venue['location'] = (new Location)
//            ->setLatitude($venue['location']['latitude'])
//            ->setLongitude($venue['location']['longitude']);
//
//        $venue['foursquare_id'] = null;
//
//        /** @var SlackReceivedMessage $message */
//        $message = $this->driver->getMessage();
//        $this->assertInstanceOf(SlackReceivedMessage::class, $message);
//        $this->assertEquals($venue, $message->getVenue());
//
//        $venue = $message->getVenue();
//        /** @var Location $location */
//        $location = $venue['location'];
//        $this->assertInstanceOf(Location::class, $location);
//        $this->assertSame($latitude, $location->getLatitude());
//        $this->assertSame($longitude, $location->getLongitude());
//        $this->assertSame($title, $venue['title']);
//        $this->assertSame($address, $venue['address']);
//        $this->assertNull($venue['foursquare_id']);
//    }
//
//    public function attachments(): array
//    {
//        return [
//            ['audio'],
//            ['document'],
//            [
//                'photo',
//                [
//                    [
//                        'file_id' => $this->faker()->uuid,
//                        'file_size' => 1,
//                        'file_path' => $this->faker()->imageUrl(),
//                        'width' => $this->faker()->randomNumber(),
//                        'height' => $this->faker()->randomNumber(),
//                    ],
//                    [
//                        'file_id' => $this->faker()->uuid,
//                        'file_size' => 2,
//                        'file_path' => $this->faker()->imageUrl(),
//                        'width' => $this->faker()->randomNumber(),
//                        'height' => $this->faker()->randomNumber(),
//                    ],
//                    [
//                        'file_id' => $this->faker()->uuid,
//                        'file_size' => 3,
//                        'file_path' => $this->faker()->imageUrl(),
//                        'width' => $this->faker()->randomNumber(),
//                        'height' => $this->faker()->randomNumber(),
//                    ],
//                ],
//            ],
//            ['sticker'],
//            ['video'],
//            ['voice'],
//        ];
//    }
}
