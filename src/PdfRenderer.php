<?php

namespace PdfEmbed;

use Omeka\Api\Representation\MediaRepresentation;
use Omeka\Media\FileRenderer\RendererInterface;
use Laminas\View\Renderer\PhpRenderer;

class PdfRenderer implements RendererInterface
{
    public function render(PhpRenderer $view, MediaRepresentation $media, array $options = [])
    {
        $siteSettings = $media->getServiceLocator()->get('Omeka\Settings\Site');

        if ($siteSettings->get('embed_pdf')) {

            return sprintf(
                '<iframe src="%s" style="width: 100%%; height: 600px;" allowfullscreen></iframe>',
                $view->escapeHtml($media->originalUrl())
            );
        } else {
            $thumbnailType = $options['thumbnailType'] ?? 'large';
            $link = array_key_exists('link', $options) ? $options['link'] : 'original';
            $attribs = $options['thumbnailAttribs'] ?? [];
            $img = $view->thumbnail($media, $thumbnailType, $attribs);
            if (!$link) {
                return $img;
            }

            $url = $this->getLinkUrl($media, $link);
            if (!$url) {
                return $img;
            }

            $title = $media->displayTitle();

            return sprintf('<a href="%s" title="%s">%s</a>', $view->escapeHtml($url), $view->escapeHtml($title), $img);
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
