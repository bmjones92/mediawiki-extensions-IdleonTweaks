const fetchJson = require('./fetch.js')
const urlGenerator = require('./url-generator.js')

/**
 * Adapts a search response from the server for use with the search box.
 * @param {string} query The search query that generated the response.
 * @param {SearchResponse} response The response returned from the server.
 * @param {boolean} showDescription Whether to display page descriptions.
 * @return {object}
 */
function adaptResponse(query, response, showDescription) {
    const results = response.pages.map((page, index) => {
        let description = showDescription ? page.description : undefined

        // If the page matches because of a redirect, then overwrite the
        // description to display which page it was redirected from.
        if (page.matched_title) {
            description = mw.message('idleon-search-redirected-from', page.matched_title).text()
        }

        const { id, title, thumbnail } = page
        return {
            value: id || -(index + 1),
            label: title,
            description,
            thumbnail,
            url: urlGenerator.fromPage(page)
        }
    })

    return {
        query,
        results
    }
}

/**
 * Builds the URL for a search fest request.
 * @param {string} query The search query.
 * @param {number} limit The maximum number of results to return.
 */
function buildRequestUrl(query, limit) {
    const restApiUrl = `${mw.config.get('wgScriptPath')}/rest.php`
    const params = new URLSearchParams({
        q: query,
        limit: limit.toString()
    })
    return `${restApiUrl}/v1/search/title?${params.toString()}`
}

/**
 * Fetches search results by comparing the page title to the query.
 * @param {string} query The search query.
 * @param {number} limit The maximum number of results to return.
 * @param {boolean} showDescription Whether to display a description.
 */
function fetchByTitle(query, limit = 10, showDescription = true) {
    const result = fetchJson(buildRequestUrl(query, limit), {
        headers: {
            accept: 'application/json'
        }
    })

    return {
        abort: result.abort,
        fetch: result.fetch.then((res) => adaptResponse(query, res, showDescription))
    }
}

module.exports = {
    fetchByTitle
}