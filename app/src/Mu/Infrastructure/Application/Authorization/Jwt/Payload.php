<?php
namespace Mu\Infrastructure\Application\Authorization\Jwt;

use Carbon\Carbon;
use Mu\Domain\Model\User\User;
use Ramsey\Uuid\Uuid;

/**
 * Class Payload
 * @package Mu\Infrastructure\Application\Authorization\Jwt\FirebaseToken
 */
class Payload
{
    const MINUTES_EXPIRE = 60;

    private $jti;
    private $iat;
    private $nbf;
    private $exp;
    private $edp;

    /**
     * @param User $user
     * @return static
     */
    public static function byUser(User $user)
    {
        $now = new Carbon();
        return new static(
            Uuid::uuid4()->toString(),
            $now->getTimestamp(),
            $now->getTimestamp(),
            $now->addMinutes(self::MINUTES_EXPIRE)->getTimestamp(),
            [
                'id' => $user->id()->toString(),
            ]
        );
    }

    public function __construct(
        string $jti,
        int $iat,
        int $nbf,
        int $exp,
        array $edp
    ) {
        $this->jti = $jti;
        $this->iat = $iat;
        $this->nbf = $nbf;
        $this->exp = $exp;
        $this->edp = $edp;
    }
}
