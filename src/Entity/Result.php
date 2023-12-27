<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JetBrains\PhpStorm\ArrayShape;
use JMS\Serializer\Annotation as Serializer;
use JsonSerializable;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Hateoas\Relation(
 *     name="parent",
 *     href="expr(constant('\\App\\Controller\\ApiResultsQueryController::RUTA_API'))"
 * )
 *
 * @Hateoas\Relation(
 *     name="self",
 *     href="expr(constant('\\App\\Controller\\ApiResultsQueryController::RUTA_API') ~ '/' ~ object.getId())"
 * )
 */
#[ORM\Entity, ORM\Table(name: "results")]
#[ORM\UniqueConstraint(name: "IDX_UNIQ_DATE", columns: [ "date" ])]
#[Serializer\XmlNamespace(uri: "http://www.w3.org/2005/Atom", prefix: "atom")]
#[Serializer\AccessorOrder(order: 'custom', custom: [ "id", "user", "result", "date" ]) ]
class Result implements JsonSerializable
{
    public final const USER_ATTR = 'user';
    public final const RESULT_ATTR = 'result';
    public final const DATE_ATTR = 'date';

    #[ORM\Column(
        name: "id",
        type: "integer",
        nullable: false
    )]
    #[ORM\Id, ORM\GeneratedValue(strategy: "IDENTITY")]
    #[Serializer\XmlAttribute]
    protected ?int $id = 0;

    #[ORM\Column(
        name: "result",
        type: "integer",
        nullable: false
    )]
    #[Serializer\SerializedName(Result::RESULT_ATTR), Serializer\XmlElement(cdata: false)]
    protected string $result;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(
        name: "user_id",
        referencedColumnName: "id",
        onDelete: "CASCADE"
    )]
    protected User $user;

    #[ORM\Column(
        name: "date",
        type: "datetime",
        nullable: false
    )]
    #[Serializer\Exclude]
    protected DateTime $date;

    /**
     * Result constructor.
     *
     * @param int $result result
     * @param User|null $user user
     * @param DateTime|null $date date
     */
    public function __construct(
        int $result = 0,
        ?User $user = null,
        ?DateTime $date = null
    ) {
        $this->id     = 0;
        $this->result = $result;
        $this->user   = $user;
        if ($date == null) {
            $this->date = new DateTime();
        } else {
            $this->date = $date;
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setResult(int $result): void
    {
        $this->result = $result;
    }

    public function setDate(DateTime $date): void
    {
        $this->date= $date;
    }

    public function getResult(): int
    {
        return $this->result;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @inheritDoc
     *
     * @return array<string,array<string>|int|string|null>
     */
    #[ArrayShape([
        'Id' => "int|null",
        self::USER_ATTR => "User",
        self::RESULT_ATTR => "int",
        self::DATE_ATTR => "DateTime"
    ])]
    public function jsonSerialize(): array
    {
        return [
            'Id' => $this->getId(),
            self::USER_ATTR => $this->getUser(),
            self::RESULT_ATTR => $this->getResult(),
            self::DATE_ATTR => $this->getDate()
        ];
    }

    /**
     * Implements __toString()
     *
     * @return string
     * @link   http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        return sprintf(
            '%3d - %3d - %22s - %s',
            $this->id,
            $this->result,
            $this->user->getEmail(),
            $this->date->format('Y-m-d H:i:s')
        );
    }
}
