<?php
namespace Mu\Infrastructure\Application\Authorization\Jwt;

use Carbon\Carbon;
use Mu\Domain\Model\User\User;
use Ramsey\Uuid\Uuid;

final class Payload
{
    const MINUTES_EXPIRE = 60;

    private $jti;
    private $iat;
    private $nbf;
    private $exp;
    private $edp;

    public static function byUser(User $user): self
    {
        $now = new Carbon();
        return new static(
            Uuid::uuid4()->toString(),
            $now->getTimestamp(),
            $now->getTimestamp(),
            $now->addMinutes(self::MINUTES_EXPIRE)->getTimestamp(),
            [
                'user' => $user,
                'role' => $user->role(),
                'permissions' => $user->role()->permissions()
            ]
        );
    }

    private function __construct(
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

    public static function byData(array $data = []): self
    {
        $now = new Carbon();
        return new static(
            Uuid::uuid4()->toString(),
            $now->getTimestamp(),
            $now->getTimestamp(),
            $now->addMinutes(self::MINUTES_EXPIRE)->getTimestamp(),
            $data
        );
    }

    /**
     * Value is a Timestamp
     * @return int
     */
    public function expiresAt(): int
    {
        return $this->exp;
    }
}
