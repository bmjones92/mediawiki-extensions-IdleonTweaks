const fetchJson = require('./fetch.js')
const urlGenerator = require('./url-generator.js')

/**
 * @typedef AdaptedSearchResponse
 * @property {SearchRequestParams} request
 * @property {Array<SearchResultMenuItem>} results
 */

/**
 * Adapts a search response from the server for use with the search box.
 * @param {SearchRequestParams} request The request parameters that generated the response.
 * @param {SearchResponse} response The response returned from the server.
 * @return {AdaptedSearchResponse}
 */
function adaptResponse(request, response) {
    const results = response.pages.map((page, index) => {
        let description = request.showDescription ? page.description : undefined

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
        request,
        results
    }
}

/**
 * Builds the URL for a search fest request.
 * @param {SearchRequestParams} params The maximum number of results to return.
 */
function buildRequestUrl({ query, limit }) {
    const searchParams = new URLSearchParams({
        q: query,
        limit: limit.toString()
    })

    const restApiUrl = `${mw.config.get('wgScriptPath')}/rest.php`
    return `${restApiUrl}/v1/search/title?${searchParams.toString()}`
}

/**
 * Fetches search results by comparing the page title to the search query.
 * @param {SearchRequestParams} params The search request parameters
 */
function fetchByTitle(params) {
    // Assign appropriate defaults for missing parameters.
    params = {
        limit: 10,
        offset: 0,
        showDescription: true,
        ...params
    }

    const url = buildRequestUrl(params)
    const result = fetchJson(url, {
        headers: {
            accept: 'application/json'
        }
    })

    return {
        abort: result.abort,
        fetch: result.fetch.then((res) => adaptResponse(params, res))
    }
}

/**
 * @typedef SearchClient
 * @property {fetchByTitle} fetchByTitle
 * @property {boolean} supportsLoadMore Currently unsupported because search API doesn't accept offsets.
 */
module.exports = {
    fetchByTitle,
    supportsOffsets: false
}