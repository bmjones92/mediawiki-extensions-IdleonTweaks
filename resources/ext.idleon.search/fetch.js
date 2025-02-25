/**
 * Creates an AbortController for a fetch request. If AbortController is not
 * available, then a dummy controller will be created instead.
 * @return {AbortController}
 */
function createAbortController() {
    return window.AbortController ? new AbortController() : {
        signal: undefined,
        abort: () => {}
    }
}

/**
 * Wrapper around the native `fetch()` function to automatically extract the JSON data.
 * @param {RequestInfo|URL} resource The resource to fetch.
 * @param {RequestInit} init The request initialization data.
 * @return {AbortableSearchFetch}
 */
function fetchJson(resource, init) {
    const controller = createAbortController()

    const json = fetch(resource, Object.assign({}, init, {
        signal: controller.signal
    })).then(response => {
        if (!response.ok) {
            return Promise.reject("Network request failed with HTTP code " + response.status);
        }
        return response.json()
    })

    return {
        fetch: json,
        abort: () => {
            controller.abort()
        }
    }
}

module.exports = fetchJson