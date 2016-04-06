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
 * @package    Property
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Model\Tool\Newsletter\Config\Listing;

use Pimcore\Model;
use Pimcore\Model\Tool\Newsletter\Config;

class Dao extends Model\Dao\PhpArrayTable
{

    /**
     *
     */
    public function configure()
    {
        parent::configure();
        $this->setFile("newsletter");
    }

    /**
     * Loads a list of predefined properties for the specicifies parameters, returns an array of Property\Predefined elements
     *
     * @return array
     */
    public function load()
    {
        $properties = array();
        $propertiesData = $this->db->fetchAll($this->model->getFilter(), $this->model->getOrder());

        foreach ($propertiesData as $propertyData) {
            $properties[] = Config::getByName($propertyData["id"]);
        }

        $this->model->setNewsletter($properties);
        return $properties;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        $data = $this->db->fetchAll($this->model->getFilter(), $this->model->getOrder());
        $amount = count($data);

        return $amount;
    }
}
