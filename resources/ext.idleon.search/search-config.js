/**
 * Properties which are passed into the search component.
 * @typedef {Object} SearchProps
 * @property {string} id The id of the search form.
 * @property {string} action The form action.
 * @property {string} autocapitalizeValue
 * @property {boolean} autofocusInput
 * @property {string} searchAccessKey
 * @property {string} searchPageTitle
 * @property {string} searchTitle
 * @property {string} searchPlaceholder
 * @property {string} searchQuery
 * @property {boolean} autoExpandWidth
 */

/**
 * @typedef {Object} SearchSkinConfiguration
 * @property {SearchProps} props The props to pass into the app.
 * @property {HTMLElement} mount The element to mount the application onto.
 */

/**
 *
 * @param {HTMLElement} searchBox The root search element.
 * @return {SearchSkinConfiguration|null}
 */
function timeless(searchBox) {
    const searchForm = searchBox.querySelector('form')
    if (!searchForm) {
        console.error('Could not find search form.')
        return null;
    }

    const titleElement = searchBox.querySelector('input[name=title]')
    const searchElement = searchBox.querySelector('input[name=search]')

    const props = {
        id: searchForm.id,
        autocapitalizeValue: searchElement.getAttribute('autocapitalize'),
        autofocusInput: searchElement === document.activeElement,
        action: searchForm.getAttribute('action'),
        searchAccessKey: searchForm.getAttribute('accessKey'),
        searchPageTitle: titleElement.value,
        searchTitle: searchElement.getAttribute('title'),
        searchPlaceholder: searchElement.getAttribute('placeholder'),
        searchQuery: searchElement.value,
        autoExpandWidth: false //searchBox.classList.contains('vector-search-box-auto-expand-width')
    }

    const mount = document.createElement('div');
    searchBox.replaceChildren(mount);

    return {
        props,
        mount
    }
}

/**
 *
 * @param {HTMLElement} searchBox
 * @return {SearchProps|null}
 */
module.exports = function(searchBox) {
    const skin = mw.config.get('skin');
    switch (skin) {
        case 'timeless':
            return timeless(searchBox);
        default:
            return null
    }
}