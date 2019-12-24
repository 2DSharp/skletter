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
    ['GET', '/', ['Home', 'main']],
    ['GET', '/api/home', ['Home', 'main', 'jsonMain']],
    ['POST', '/login', ['Login', 'attemptLogin']],
    ['GET', '/login', ['Login', 'renderLoginPage']],
    ['GET', '/register', ['Registration', 'displayForm']],
    ['POST', '/register', ['Registration', 'registerUser']],
    ['GET', '/confirm', ['Confirmation', 'confirmRegistrationWithToken']],
    ['POST', '/confirm', ['Confirmation', 'confirmRegistrationWithPin']],
    ['GET', '/api/suggest', ['Search', 'look']],
    ['GET', '/test', ['SearchService', 'testSearch']],
    ['POST', '/api/uploadPicture', ['UploadPicture', 'upload']],
    ['GET', '/api/getCurrentUserDetails', ['Profile', 'getCurrentUserDetails']],
    ['GET', '/api/getProfilePicture', ['Profile', 'displayProfilePicture']]
];