/**
 * Describes the thumbnail to display for a search result.
 * @typedef SearchResultThumbnail
 * @property {string} mimetype The image mimetype
 * @property {number?} size The file size of the image.
 * @property {number?} width The width of the image.
 * @property {number?} height The height of the image.
 * @property {number?} duration The length of the multimedia file.
 * @property {string} url The URL to download the file.
 */

/**
 * Describes a page which matched a search query.
 * @typedef SearchResultPage
 * @property {number} id Page identifier
 * @property {string} key Page title in a URL-friendly format.
 * @property {string} title Page title in a human-readable format.
 * @property {string} excerpt
 * @property {string?} matched_title Title of the redirect page if the query originally matched a redirect page.
 * @property {string?} description A short summary of the page.
 * @property {SearchResultThumbnail?} thumbnail The thumbnail for the page.
 */

/**
 * Describes the response generated from querying the search REST API.
 * @typedef SearchResponse
 * @property {SearchResultPage[]} pages The set of pages which matched the query.
 */

/**
 * Describes the properties for a search result in the search menu.
 * @typedef SearchResultMenuItem
 * @property {string|number} value Item value or unique identifier.
 * @property {string?} label Display label for the menu item.
 * @property {string?} match Text to be appended to the result's label.
 * @property {string?} description The subtext for this menu item.
 * @property {object?} language (UNUSED) Language attributes of text properties.
 * @property {object?} icon (UNUSED) The result icon.
 * @property {SearchResultThumbnail?} thumbnail The thumbnail.
 * @property {string} url The URL of the target page.
 * @property {boolean?} disabled Whether the result is disabled.
 */

/**
 * Describes the optional search parameters which can be passed to the search API.
 * @typedef SearchRequestParams
 * @property {string} query The search query.
 * @property {number?} limit The maximum number of results to return.
 * @property {number?} offset The number of results to skip before the first result.
 * @property {boolean?} showDescription Whether results should contain a description.
 */
