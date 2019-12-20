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


use Skletter\Model\RemoteService\PhotoBooth\CropBox;
use Skletter\Model\RemoteService\PhotoBooth\ImageMeta;
use Skletter\Model\RemoteService\PhotoBooth\ImageType;
use Skletter\Model\RemoteService\PhotoBooth\PhotoBoothClient;

class ImageService
{
    /**
     * @var PhotoBoothClient
     */
    private PhotoBoothClient $photoBooth;

    public function __construct(PhotoBoothClient $photoBooth)
    {
        $this->photoBooth = $photoBooth;
    }

    public function getImageId(): string
    {
        return $this->photoBooth->generateImageId();
    }

    public function updateProfilePicture(string $imageId, string $imagePath, array $cropData)
    {
        $meta = new ImageMeta();
        $cropBox = new CropBox();
        $cropBox->x = $cropData['x'];
        $cropBox->y = $cropData['y'];
        $cropBox->side = $cropData['side'];

        $meta->cropBox = $cropBox;
        $meta->type = ImageType::DISPLAY_PICTURE;

        $this->photoBooth->uploadImage($imageId, $imagePath, $meta);
    }
}