<?php
/**
 * Loi Hamon Prestashop module
 *
 * @author    Prestaddons <contact@prestaddons.fr>
 * @copyright 2014 Prestaddons
 * @license
 * @link      http://www.prestaddons.fr
 */

class HTMLTemplateCMS extends HTMLTemplate
{
    const MODULE_NAME = 'loihamon';

    public function __construct($cms, $smarty)
    {
        $this->cms = $cms;
        $this->smarty = $smarty;
        $context = Context::getContext();

        // header informations
        $this->title = $this->cms->meta_title;

        // footer informations
        $this->shop = new Shop((int)$context->shop->id);
    }

    /**
     * Returns the template's HTML header
     *
     * @return string HTML header
     */
    public function getHeader()
    {
        if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
            $this->assignCommonHeaderData();
            $this->smarty->assign(array('header' => HTMLTemplateCMS::l('CGV')));

            return $this->smarty->fetch($this->getTemplate('header'));
        } else {
            return parent::getHeader();
        }
    }

    /**
     * Returns the template's HTML content
     * @return string HTML content
     */
    public function getContent()
    {
        $this->smarty->assign(array(
            'content' => $this->cms->content
        ));

        return $this->smarty->fetch(_PS_MODULE_DIR_.self::MODULE_NAME.'/views/templates/hook/cms.tpl');
    }

    /**
     * Returns the template filename when using bulk rendering
     * @return string filename
     */
    public function getBulkFilename()
    {
        return 'cms.pdf';
    }

    /**
     * Returns the template filename
     * @return string filename
     */
    public function getFilename()
    {
        return $this->getBulkFilename();
    }

    /**
     * Returns the template's HTML pagination block
     *
     * @return string HTML pagination block
     */
    public function getPagination()
    {
        return $this->smarty->fetch($this->getTemplate('pagination'));
    }
}
