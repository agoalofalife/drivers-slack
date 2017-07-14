<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Client;
use Mockery;
use Faker\Factory;
use Faker\Generator;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    protected function faker(): Generator
    {
        return Factory::create();
    }

    /**
     * @param string $class
     *
     * @return \Mockery\Mock|mixed
     */
    protected function mock(string $class)
    {
        return Mockery::mock($class);
    }

    /**
     * @return mixed|Mockery\Mock
     */
    protected function guzzle()
    {
        return $this->guzzle ?? $this->mock(Client::class);
    }

    protected function factoryTypeRequest($mode = false) : array
    {
        $faker = $this->faker();
        $arguments =  [
                 0 => [
                'type' => $faker->word,
                'token' => $this->getToken(),
                'event' => [
                    'user' => (string) $faker->randomDigit,
                    'text' => $faker->text,
                    'channel' => $faker->randomLetter,
                ]],
                 1 => [
                    'token' => $this->getToken(),
                    'user_id' => $faker->word,
                    'channel_id' => $faker->randomLetter,
                    'command' => $faker->text,
                    'text' => $faker->text
                 ],
                 2 => [
                    'payload' => '{"actions":[{"value":"test"}], "token": "'. $this->getToken() .'"}'
                 ],
                 3 => [
                    'payload' => '{"actions":[{"selected_options":"test"}], "token": "'. $this->getToken() .'"}'
                 ]
        ];
        if ($mode === false) {
            return $arguments[0];
        } else {
            return $arguments;
        }
    }

    /**
     * Get user info generate
     *
     * @link https://api.slack.com/methods/users.info
     * @param array $additional
     * @return string
     */
    protected function factoryUserInfo(array $additional = []) : string
    {
        $faker = $this->faker();
        return json_encode(array_merge(
            [
                "ok" =>  $faker->boolean(),
                "user" => [
                    "id"  => $faker->randomLetter,
                    "name"=> $faker->name,
                    "deleted" => $faker->boolean(),
                    "color"   => $faker->hexcolor,
                    "profile" => [
                        "first_name"=> $faker->userName,
                        "last_name"=> $faker->lastName,
                        "real_name"=> $faker->name,
                    ]
                ]
            ],
            $additional
        ));
    }

    /**
     * @return string
     */
    public function getToken() : string
    {
        return 'test';
    }

    /**
     * @param array $keys
     * @param array $source
     */
    public function assertArrayHasKeys(array $keys, array $source) : void
    {
        array_map(function ($key) use ($source) {
            $this->assertArrayHasKey($key, $source);
        }, $keys);
    }
}
