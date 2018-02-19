<?php declare(strict_types=1);

namespace AppBundle\SeoPage;

use Criticalmass\Bundle\AppBundle\EntityInterface\PhotoInterface;
use Criticalmass\Bundle\AppBundle\EntityInterface\RouteableInterface;
use Criticalmass\Component\Router\ObjectRouter;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class SeoPage
{
    /** @var SeoPageInterface */
    protected $sonataSeoPage;

    public function __construct(SeoPageInterface $sonataSeoPage)
    {
        $this->sonataSeoPage = $sonataSeoPage;
    }

    public function setTitle(string $title): SeoPage
    {
        $this->sonataSeoPage
            ->setTitle($title)
            ->addMeta('property', 'og:title', $title)
        ;

        return $this;
    }

    public function setDescription(string $description): SeoPage
    {
        $this->sonataSeoPage
            ->addMeta('name', 'description',$description)
            ->addMeta('property', 'og:description', $description)
        ;

        return $this;
    }

    /*
    public function setPreviewPhoto(PhotoInterface $object): SeoPage
    {
        if (!$object->getImageName()) {
            return $this;
        }
        
        $imageFilename = $this->uploaderHelper->asset($object, 'imageFile');

        $facebookPreviewPath = $this->cacheManager->getBrowserPath($imageFilename, 'facebook_preview_image');
        $twitterPreviewPath = $this->cacheManager->getBrowserPath($imageFilename, 'twitter_summary_large_image');

        $this->sonataSeoPage
            ->addMeta('property', 'og:image', $facebookPreviewPath)
            ->addMeta('name', 'twitter:image', $twitterPreviewPath)
            ->addMeta('name', 'twitter:card', 'summary_large_image')
        ;

        return $this;
    }*/

    public function setCanonicalLink(string $link): SeoPage
    {
        $this->sonataSeoPage
            ->setLinkCanonical($link)
            ->addMeta('property', 'og:url', $link)
        ;

        return $this;
    }
}
