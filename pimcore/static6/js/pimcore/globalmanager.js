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

pimcore.registerNS("pimcore.globalmanager");
pimcore.globalmanager = {
    store: {},

    add: function (key, value) {
        this.store[key] = value;
    },

    remove: function (key) {
        try {
            if (this.store[key]) {
                delete this.store[key];
            }
        }
        catch (e) {
            console.log("failed to remove " + key + " from cache");
        }

    },

    exists: function (key) {
        if (this.store[key]) {
            return true;
        }
        return false;
    },

    get: function (key) {
        if (this.store[key]) {
            return this.store[key];
        }
        return false;
    }
};