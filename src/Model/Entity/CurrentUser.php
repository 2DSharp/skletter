<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Entity;


use Skletter\Model\RemoteService\Romeo\Status;
use Skletter\Model\RemoteService\Romeo\UserDTO;

class CurrentUser extends User
{
    private int $status;
    private string $email;
    const TEMP = 0;

    const ACTIVE = 1;

    const DEACTIVATED = 2;

    const RECOVERY = 3;

    const SUSPENDED = 4;
    public static array $statusMap = [
        Status::ACTIVE => self::ACTIVE,
        Status::DEACTIVATED => self::DEACTIVATED,
        Status::RECOVERY => self::RECOVERY,
        Status::SUSPENDED => self::SUSPENDED,
        Status::TEMP => self::TEMP
    ];

    /**
     * CurrentUser constructor.
     * @param int $id
     * @param string $name
     * @param string $username
     * @param string $email
     * @param int $status
     */
    public function __construct(int $id, string $name, string $username, string $email, int $status)
    {
        parent::__construct($id, $name, $username);
        $this->email = $email;
        $this->status = $status;
    }

    public static function buildFromDTO(UserDTO $dto): CurrentUser
    {
        return new CurrentUser($dto->id, $dto->name, $dto->username, $dto->email,
                               CurrentUser::$statusMap[$dto->status]);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}