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

pimcore.registerNS("pimcore.document.tags.embed");
pimcore.document.tags.embed = Class.create(pimcore.document.tag, {

    initialize: function(id, name, options, data, inherited) {
        this.id = id;
        this.name = name;
        this.data = {};

        this.options = this.parseOptions(options);
        this.data = data;

        this.setupWrapper();

        var element = Ext.get(id);

        var button = new Ext.Button({
            iconCls: "pimcore_icon_embed pimcore_icon_overlay_edit",
            cls: "pimcore_edit_link_button",
            handler: this.openEditor.bind(this)
        });
        button.render(element.insertHtml("afterBegin", '<div class="pimcore_video_edit_button"></div>'));

        if(empty(this.data["url"])) {
            element.addCls("pimcore_tag_embed_empty");
            element.on("click", this.openEditor.bind(this));
        }
    },

    openEditor: function () {

        // disable the global dnd handler in this editmode/frame
        window.dndManager.disable();

        parent.Ext.MessageBox.prompt("", t('please_enter_url'),
        function (button, value, object) {
            if(button == "ok") {
                this.data["url"] = value;
                this.reloadDocument();
            }
        }.bind(this), this, false, this.data["url"]);
    },

    getValue: function () {
        return this.data;
    },

    getType: function () {
        return "embed";
    }
});