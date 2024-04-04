<?php
namespace PdfEmbed;

use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Omeka\Module\AbstractModule;

class Module extends AbstractModule
{

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function getConfig()
    {
            return include __DIR__ . '/config/module.config.php';
    }

    public function addSiteSettings(Event $event)
    {
        $services = $this->getServiceLocator();
        $siteSettings = $services->get('Omeka\Settings\Site');
        $form = $event->getTarget();

        $groups = $form->getOption('element_groups');
        $groups['pdfembed'] = 'PDF Embed'; // @translate
        $form->setOption('element_groups', $groups);

        $form->add([
            'type' => 'checkbox',
            'name' => 'embed_pdf',
            'options' => [
                'element_group' => 'pdfembed',
                'label' => 'Embed Pdfs in pages', // @translate
            ],
            'attributes' => [
                'value' => $siteSettings->get('embed_pdf'),
            ],
        ]);

    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $sharedEventManager->attach(
            'Omeka\Form\SiteSettingsForm',
            'form.add_elements',
            [$this, 'addSiteSettings']
        );

    }

}
