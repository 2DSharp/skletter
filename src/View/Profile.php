<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\View;


use Skletter\Model\LocalService\SessionManager;
use Skletter\Model\Mediator\AccountService;
use Skletter\Model\Mediator\ImageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Profile extends AbstractView
{
    private ImageService $imageService;
    private AccountService $accountSevice;
    /**
     * @var SessionManager
     */
    private SessionManager $session;

    /**
     * Profile constructor.
     * @param ImageService $imageService
     * @param SessionManager $sessionManager
     * @param AccountService $accountService
     */
    public function __construct(ImageService $imageService,
                                SessionManager $sessionManager,
                                AccountService $accountService)
    {
        $this->imageService = $imageService;
        $this->session = $sessionManager;
        $this->accountSevice = $accountService;
    }


    public function displayProfilePicture(Request $request): Response
    {
        $imageId = $this->accountSevice->getProfilePicture($request->query->get("username"));
        return new JsonResponse(['url' => str_replace('/var/www/Skletter/public/static/upload/',
                                                      $_ENV['USER_IMAGES'] . "/",
                                                      $this->imageService->getProfilePicVariant($imageId,
                                                                                                ImageService::BIG))]);
    }

    public function getCurrentUserDetails(Request $request): Response
    {
        $details = $this->session->getLoginDetails();
        return new JsonResponse([
                                    'name' => $details->name,
                                    'email' => $details->email,
                                    'username' => $details->username
                                ]);
    }
}