pimcore.registerNS("pimcore.plugin.PurushCstoreBundle");

pimcore.plugin.PurushCstoreBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.PurushCstoreBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("PurushCstoreBundle ready!");
    }
});

var PurushCstoreBundlePlugin = new pimcore.plugin.PurushCstoreBundle();