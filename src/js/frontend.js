/**
 * Popup Notices for WooCommerce.
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory
 */

// Loads modules dynamically and asynchronously
__webpack_public_path__ = ttt_pnwc_info.pluginURL + "/assets/";
let modules = ttt_pnwc_info.modulesRequired;
if (modules && modules.length) {
    modules.forEach(function (module) {
        import(
            /* webpackMode: "lazy"*/
            `./modules/${module}`)
            .then(function (component) {
                if (document.readyState !== 'loading') {
                    component.init();
                } else {
                    document.addEventListener('DOMContentLoaded', function () {
                        component.init();
                    });
                }
            });
    });
}

// Loads modules manually and synchronously
import module from './modules/general.js';
document.addEventListener('DOMContentLoaded', function () {
    module.init();
});