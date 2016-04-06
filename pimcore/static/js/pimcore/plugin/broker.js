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

pimcore.registerNS("pimcore.plugin.broker");
pimcore.plugin.broker = {

    plugins: new Array(),

    initialize: function() {

    },

    registerPlugin: function(plugin) {
        this.plugins.push(plugin);
    },

    getPlugins: function() {
        return this.plugins;
    },

    pluginsAvailable: function () {
        var size;

        if (this.plugins != null && this.plugins.size() > 0) {
            return this.plugins.size();
        }
        return 0;
    },

    executePlugin: function (plugin, event, params) {
        if (typeof plugin[event] == "function") {
            params.push(this);
            plugin[event].apply(plugin, params);
        }
    },

    fireEvent: function (e) {
        var plugin;
        var size = this.pluginsAvailable();
        var args = $A(arguments);
        args.splice(0, 1);

        for (var i = 0; i < size; i++) {
            plugin = this.plugins[i];
            try {
                this.executePlugin(plugin, e, args);
            } catch (e) {
                console.error(e);
            }
        }
    }
};