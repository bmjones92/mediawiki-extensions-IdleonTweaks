/**
 */
function generateUrl(parameters) {

}

/**
 * Generates a page URL for a page result.
 * @param {SearchResultPage} page
 */
function fromPage(page) {
    // The search API only exposes the target page for redirects. This breaks
    // redirects which redirect to a specific section of a page. We can work
    // around this by navigating to the redirect instead and letting the server
    // take us where we need to go.
    const title = page.matched_title ? page.matched_title : page.title
    return mw.Title.newFromText(title).getUrl()
}

/**
 * Generates a URL from a search query.
 * @param {string} query
 */
function fromQuery(query) {
    const params = new URLSearchParams({
        title: "Special:Search",
        fulltext: "1",
        search: query
    })

    const scriptPath = mw.config.get("wgScript")
    return `${scriptPath}?${params.toString()}`
}

/**
 *
 * @type {generateUrl}
 */
module.exports = {
    fromPage,
    fromQuery
}