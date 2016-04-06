<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in 
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

use Pimcore\Model\Document;
use Pimcore\Model\Asset;
use Pimcore\Model\Element;

class Admin_LinkController extends \Pimcore\Controller\Action\Admin\Document
{

    public function getDataByIdAction()
    {

        // check for lock
        if (Element\Editlock::isLocked($this->getParam("id"), "document")) {
            $this->_helper->json(array(
                "editlock" => Element\Editlock::getByElement($this->getParam("id"), "document")
            ));
        }
        Element\Editlock::lock($this->getParam("id"), "document");

        $link = Document\Link::getById($this->getParam("id"));
        $link = clone $link;

        $link->setObject(null);
        $link->idPath = Element\Service::getIdPath($link);
        $link->userPermissions = $link->getUserPermissions();
        $link->setLocked($link->isLocked());
        $link->setParent(null);

        $this->addTranslationsData($link);
        $this->minimizeProperties($link);

        if ($link->isAllowed("view")) {
            $this->_helper->json($link);
        }

        $this->_helper->json(false);
    }

    public function saveAction()
    {
        try {
            if ($this->getParam("id")) {
                $link = Document\Link::getById($this->getParam("id"));
                $this->setValuesToDocument($link);

                $link->setModificationDate(time());
                $link->setUserModification($this->getUser()->getId());

                if ($this->getParam("task") == "unpublish") {
                    $link->setPublished(false);
                }
                if ($this->getParam("task") == "publish") {
                    $link->setPublished(true);
                }

                // only save when publish or unpublish
                if (($this->getParam("task") == "publish" && $link->isAllowed("publish")) || ($this->getParam("task") == "unpublish" && $link->isAllowed("unpublish"))) {
                    $link->save();

                    $this->_helper->json(array("success" => true));
                }
            }
        } catch (\Exception $e) {
            \Logger::log($e);
            if (\Pimcore\Tool\Admin::isExtJS6() && $e instanceof Element\ValidationException) {
                $this->_helper->json(array("success" => false, "type" => "ValidationException", "message" => $e->getMessage(), "stack" => $e->getTraceAsString(), "code" => $e->getCode()));
            }
            throw $e;
        }

        $this->_helper->json(false);
    }

    protected function setValuesToDocument(Document\Link $link)
    {

        // data
        $data = \Zend_Json::decode($this->getParam("data"));

        if (!empty($data["path"])) {
            if ($document = Document::getByPath($data["path"])) {
                $data["linktype"] = "internal";
                $data["internalType"] = "document";
                $data["internal"] = $document->getId();
            } elseif ($asset = Asset::getByPath($data["path"])) {
                $data["linktype"] = "internal";
                $data["internalType"] = "asset";
                $data["internal"] = $asset->getId();
            } else {
                $data["linktype"] = "direct";
                $data["direct"] = $data["path"];
            }
        }

        unset($data["path"]);

        $link->setValues($data);
        $this->addPropertiesToDocument($link);
    }
}
