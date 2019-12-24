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
use Skletter\Model\LocalService\SessionManager;
use Skletter\Model\Mediator\AccountService;
use Skletter\Model\Mediator\ImageService;
use Symfony\Component\HttpFoundation\Request;

class UploadPicture implements Controller
{
    const FIFTEEN_MB = 15000000;
    const ONE_KB = 1000;
    /**
     * @var SessionManager
     */
    private SessionManager $session;
    /**
     * @var ImageService
     */
    private ImageService $imageService;
    /**
     * @var AccountService
     */
    private AccountService $account;

    use ControllerTrait;

    public function __construct(ImageService $imageService,
                                AccountService $account,
                                SessionManager $sessionManager)
    {
        $this->imageService = $imageService;
        $this->account = $account;
        $this->session = $sessionManager;
    }

    public function upload(Request $request)
    {
        $image = new Image($_FILES);

        $imageId = $this->imageService->getImageId();
        $image->setName($imageId)
            ->setSize(self::ONE_KB, self::FIFTEEN_MB)
            ->setDimension(12000, 12000)
            ->setMime(["jpeg"])
            ->setLocation(__DIR__ . "/../../temp_store");


        if ($image["avatar"] && $image->upload()) {
            $values = [
                'x' => $request->request->get('x'),
                'y' => $request->request->get('y'),
                'side' => $request->request->get('side')
            ];
            $this->imageService->updateProfilePicture($imageId,
                                                      $image->getFullPath(),
                                                      $values);
            $this->account->updateProfilePicture($this->session->getLoginDetails()->id, $imageId);
            //echo $this->imageService->getProfilePicVariant($imageId, ImageService::BIG);
            return [
                'url' => $_ENV['USER_IMAGES'] . "/" . $image->getName() . ".jpg",
            ];
        }
        return ['url' => 'not found'];
    }
}