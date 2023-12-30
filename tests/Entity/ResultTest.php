<?php

/**
 * @category TestEntities
 * @package  App\Tests\Entity
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://miw.etsisi.upm.es/ E.T.S. de Ingeniería de Sistemas Informáticos
 */

namespace App\Tests\Entity;

use _PHPStan_39fe102d2\Nette\Utils\DateTime;
use App\Entity\Result;
use App\Entity\User;
use Exception;
use Faker\Factory as FakerFactoryAlias;
use Faker\Generator as FakerGeneratorAlias;
use PHPUnit\Framework\TestCase;

/**
 * Class ResultTest
 *
 * @package App\Tests\Entity
 *
 * @group   entities
 * @coversDefaultClass \App\Entity\User
 */
class ResultTest extends TestCase
{
    protected static Result $result;
    protected static User $user;
    private static FakerGeneratorAlias $faker;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     */
    public static function setUpBeforeClass(): void
    {
        self::$faker = FakerFactoryAlias::create('es_ES');
        self::$user = new User('test@example.com', 'password', ['ROLE_USER']);
        self::$result = new Result(42, self::$user, new DateTime());
    }

    /**
     * Implement testConstructor().
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $result = new Result(0, self::$user, new DateTime());
        self::assertSame(0, $result->getId());
        self::assertEmpty($result->getResult());
        self::assertSame(self::$user, $result->getUser());
        self::assertInstanceOf(DateTime::class, $result->getDate());
        self::assertLessThanOrEqual(new DateTime(), $result->getDate());
    }

    /**
     * Implement testGetId().
     *
     * @return void
     */
    public function testGetId(): void
    {
        self::assertSame(0, self::$result->getId());
    }

    /**
     * Implements testGetSetResult().
     *
     * @throws Exception
     * @return void
     */
    public function testGetSetResult(): void
    {
        $resultResult = self::$faker->numberBetween(10, 100);
        self::$result->setResult($resultResult);
        self::assertSame(
            $resultResult,
            self::$result->getResult()
        );
    }

    /**
     * Implements testGetSetDate().
     *
     * @throws Exception
     * @return void
     */
    public function testGetSetDate(): void
    {
        $resultDate = new DateTime('@' . self::$faker->unixTime());
        self::$result->setDate($resultDate);
        self::assertSame(
            $resultDate,
            self::$result->getDate()
        );
    }

    /**
     * Implement testGetSetUser().
     *
     * @return void
     */
    public function testGetSetUser(): void
    {
        self::assertInstanceOf(User::class, self::$result->getUser());
        self::assertSame(
            "test@example.com",
            self::$result->getUser()->getEmail()
        );
        self::assertSame(
            "password",
            self::$result->getUser()->getPassword()
        );
    }
}
