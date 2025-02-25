(function() {
    const Vue = require('vue');
    const App = require('./App.vue')
    const createSearchConfig = require('./search-config.js')

    // Find the root search element.
    const searchElement = document.querySelector('#p-search');
    if (!searchElement) {
        console.error('Could not find search element. Not mounting enhanced search.');
        return;
    }

    // Create the application properties for the search element.
    const searchConfig = createSearchConfig(searchElement);
    if (!searchConfig) {
        console.error('Unable to mount enhanced search because current skin is unsupported.');
        return;
    }

    // Mount the search app.
    Vue.createMwApp(App, searchConfig.props).mount(searchConfig.mount);
}())
