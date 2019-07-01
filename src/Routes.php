<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    ['GET', '/loadHome', ['Home', 'homeBuild']],
    ['GET', '/another-route', function () {
        echo 'This works too';
    }],
];