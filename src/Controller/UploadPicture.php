<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Controller;


use Bulletproof\Image;
use Skletter\Component\Core\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UploadPicture implements Controller
{
    use ControllerTrait;

    public function upload(Request $request)
    {
        // var_dump($_FILES);
        $image = new Image($_FILES);

        $image->setName("trial123")
            ->setLocation(__DIR__ . "/../../public/static/upload");

        if ($image["avatar"] && $image->upload()) {
            // update to db
        }
        echo $image->getError();
        return new JsonResponse([], 401);
    }
}