<?php

namespace PdfEmbed;

use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\RendererInterface;
use Laminas\View\Renderer\PhpRenderer;
use Omeka\Media\FileRenderer\ThumbnailRenderer;

class PdfRenderer implements RendererInterface
{
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        $siteSettings = $media->getServiceLocator()->get('Omeka\Settings\Site');

        if ($siteSettings->get('disable_embed_pdf')) {
            $thumb = new ThumbnailRenderer;
            return $thumb->render($view, $media);

        } else {

            return sprintf(
                '<iframe src="%s" style="width: 100%%; height: 600px;" allowfullscreen></iframe>',
                $view->escapeHtml($media->originalUrl())
            );
        }
    }

    protected function getLinkUrl(MediaRepresentation $media, $linkType)
    {
        switch ($linkType) {
            case 'original':
                return $media->originalUrl();
            case 'item':
                return $media->item()->url();
            case 'media':
                return $media->url();
            default:
                throw new \InvalidArgumentException(sprintf('Invalid link type "%s"', $linkType));
        }
    }

}
