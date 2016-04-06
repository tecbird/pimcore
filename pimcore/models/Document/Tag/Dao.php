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
 * @category   Pimcore
 * @package    Document
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Model\Document\Tag;

use Pimcore\Model;

class Dao extends Model\Dao\AbstractDao
{

    /**
     *
     */
    public function save()
    {
        $data = $this->model->getDataForResource();
        
        if (is_array($data) or is_object($data)) {
            $data = \Pimcore\Tool\Serialize::serialize($data);
        }

        $element = array(
            "data" => $data,
            "documentId" => $this->model->getDocumentId(),
            "name" => $this->model->getName(),
            "type" => $this->model->getType()
        );

        $this->db->insertOrUpdate("documents_elements", $element);
    }

    /**
     *
     */
    public function delete()
    {
        $this->db->delete("documents_elements", $this->db->quoteInto("documentId = ?", $this->model->getDocumentId()) . " AND " . $this->db->quoteInto("name = ?", $this->model->getName()));
    }
}
