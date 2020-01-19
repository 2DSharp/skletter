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
use Twig\Environment;

class Profile extends AbstractView
{
    private array $variantMap = [
        'big' => ImageService::BIG,
        'small' => ImageService::SMALL,
        'normal' => ImageService::NORMAL
    ];
    private ImageService $imageService;
    private AccountService $accountService;
    /**
     * @var SessionManager
     */
    private SessionManager $session;
    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * Profile constructor.
     * @param ImageService $imageService
     * @param SessionManager $sessionManager
     * @param AccountService $accountService
     */
    public function __construct(ImageService $imageService,
                                SessionManager $sessionManager,
                                Environment $twig,
                                AccountService $accountService)
    {
        $this->imageService = $imageService;
        $this->session = $sessionManager;
        $this->twig = $twig;
        $this->accountService = $accountService;
    }

    public function displayProfile(Request $request): Response
    {
        $username = str_replace('/', "", $request->getPathInfo());
        $img = $this->accountService->getProfilePicture($username);
        $html = $this->createHTMLFromTemplate(
            $this->twig, 'pages/profile.twig',
            ['title' => $username,
                'username' => $username,
                'pic' => $img
            ]
        );
        //$this->accountService->getUserDetails($username);
        return $this->respond($request, $html);
        // return new Response(str_replace('/', "", $request->getPathInfo()));
    }

    public function displayProfilePicture(Request $request): Response
    {
        $imageId = $this->accountService->getProfilePicture($request->query->get("username"));
        $type = $this->variantMap[$request->query->get("variant", "normal")];
        return new JsonResponse(['url' => str_replace('/var/www/Skletter/public/static/upload/',
                                                      $_ENV['USER_IMAGES'] . "/",
                                                      $this->imageService->getProfilePicVariant($imageId,
                                                                                                $type))]);
    }

    public function getCurrentUserDetails(Request $request): Response
    {
        $details = $this->session->getLoginDetails();
        return new JsonResponse([
                                    'name' => $details->getName(),
                                    'email' => $details->getEmail(),
                                    'username' => $details->getUsername()
                                ]);
    }
}