<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Component;


use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TTransport;

class TransportCollector
{
    private $transports = [];

    public function add(TTransport $transport)
    {
        array_push($this->transports, $transport);
    }

    public function closeAll()
    {
        /**
         * @var TFramedTransport $transport
         */
        foreach ($this->transports as $transport) {
            if ($transport->isOpen())
                $transport->close();
        }
    }
}