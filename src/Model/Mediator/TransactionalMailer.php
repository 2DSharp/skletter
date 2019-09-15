<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Mediator;


use Skletter\Contract\Component\Mailer;

class TransactionalMailer
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendAccountConfirmationEmail(string $email)
    {
        $data = json_encode(
            [
                'address' => $email,
                'type' => 'confirmation'
            ]
        );
        $this->mailer->send($data);
    }
}