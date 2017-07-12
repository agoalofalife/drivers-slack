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
    protected function guzzle() {
        return $this->guzzle ?? $this->mock(Client::class);
    }

    protected function factoryTypeRequest() : array
    {
        $faker = $this->faker();

        return [
          'type' => $faker->word,
            'event' => [
                'user' => (string) $faker->randomDigit,
                'text' => $faker->text,
                'channel' => $faker->randomLetter
            ]
        ];
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
        return json_encode( array_merge(
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
            ], $additional));
    }
}
