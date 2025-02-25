<script>
const { ref, nextTick } = require("vue");
const { CdxTypeaheadSearch } = require("../codex.js");

const client = require('./rest-client.js')
const urlGenerator = require('./url-generator.js')

module.exports = {
  name: "EnhancedSearch",
  components: {
    CdxTypeaheadSearch
  },
  props: {
    /**
     * The value to give the search form's `action` attribute.
     */
    action: {
      type: String,
      required: true
    },
    /**
     * The value to give the search field's `autocapitalize` attribute.
     */
    autocapitalizeValue: {
      type: String,
      default: undefined
    },
    /**
     * Whether to expand the width of the search field to match the width of
     * the result menu.
     */
    autoExpandWidth: {
      type: Boolean,
      default: false
    },
    /**
     * Whether the search field should initially have focus.
     */
    autofocusInput: {
      type: Boolean,
      default: false
    },
    /**
     * Whether the current search query should be highlighted in the search
     * results. For example, bolding the part of each result that matches
     * the query string.
     */
    highlightQuery: {
      type: Boolean,
      default: true
    },
    /**
     * The value to give the search field's id attribute.
     */
    id: {
      type: String,
      required: true
    },
    /**
     * The keyboard shortcut to automatically focus search.
     */
    searchAccessKey: {
      type: String,
      default: undefined
    },
    /**
     * The title of the wiki page which displays search results.
     */
    searchPageTitle: {
      type: String,
      default: 'Special:Search'
    },
    /**
     * The value to give the search field's `placeholder` attribute. Displays
     * when the search field is empty.
     */
    searchPlaceholder: {
      type: String,
      default: undefined
    },
    /**
     * The value to give the search field's `title` attribute. Should display
     * the keyboard shortcut for focusing search.
     */
    searchTitle: {
      type: String,
      default: undefined
    },
    /**
     * The search field's initial input taken from the SSR output immediately
     * before the client's initial render.
     */
    searchQuery: {
      type: String,
      default: undefined
    },
    /**
     * Whether to display the article's description in the search results.
     */
    showDescription: {
      type: Boolean,
      default: false,
    },
    /**
     * Whether to display the article's thumbnail in the search results.
     */
    showThumbnail: {
      type: Boolean,
      default: true
    },
  },
  data() {
    return {
      /**
       * The list of suggestions to display in the search result menu.
       */
      suggestions: [],

      /**
       * The URL of the search result page for the current query.
       */
      searchFooterUrl: '',

      /**
       * The current search input. Used to detect whether a fetch response is
       * stale.
       */
      currentSearchQuery: '',

      /**
       * Whether to apply a CSS class that disables transitions on the search
       * field.
       */
      disableTransitions: this.autofocusInput,

      /**
       * Whether the search field currently has focus.
       */
      isFocused: false
    }
  },
  computed: {
    /**
     * Conditional classes to apply to input based on state.
     * @return Object.<string, boolean>
     */
    rootClasses() {
      return {
        'mw-enhanced-search-box-disable-transitions': this.disableTransitions,
        'mw-enhanced-search-typeahead-search--active': this.isFocused
      }
    },

    /**
     * The number of items to display in the result menu.
     * @return {number|null}
     */
    visibleItemLimit() {
      return client.supportsOffsets ? 7 : null
    }
  },
  methods: {

    /**
     * Fetch suggestions when new input is received.
     * @param {string} value The current search input.
     */
    onInput(value) {
      const query = value.trim()
      this.currentSearchQuery = query

      // Display no results when search query is empty
      if (query === '') {
        this.suggestions = []
        this.searchFooterUrl = ''
        return
      }

      // Update the search results
      const search = client.fetchByTitle({
        query,
        limit: 10,
        showDescription: this.showDescription
      })
      this.updateSearchResults(search, true)
    },

    /**
     * Updates the search results.
     * @param {AbortableSearchFetch} search The search fetch query.
     * @param {boolean} replaceResults Whether to clear existing results.
     */
    updateSearchResults(search, replaceResults) {
      // Keep track of the query so we can discard results if the query ends.
      const query = this.currentSearchQuery

      search.fetch.then(data => {
        // Ensure the results are up to date
        if (this.currentSearchQuery === query) {
          // Clear existing results if this is a new search
          if (replaceResults) {
            this.suggestions = []
          }

          this.suggestions.push(...data.results);

          // Generate the footer URL from the current query
          this.searchFooterUrl = urlGenerator.fromQuery(query)
        }
      }).catch(() => {
        // TODO Error handling
      })
    },

    onLoadMore() {

    }
  },
  mounted() {
    if (this.autofocusInput) {
      this.$refs.searchForm.focus()
      nextTick(() => {
        this.disableTransitions = false
      })
    }
  }
}
</script>

<template>
  <cdx-typeahead-search
      button-label="Search"
      class="mw-enhanced-search"
      ref="searchForm"
      :accesskey="searchAccessKey"
      :aria-label="searchPlaceholder"
      :autocapitalize="autocapitalizeValue"
      :auto-expand-width="autoExpandWidth"
      :class="rootClasses"
      :form-action="action"
      :highlight-query="highlightQuery"
      :id="id"
      :initial-input-value="searchQuery"
      :placeholder="searchPlaceholder"
      :search-footer-url="searchFooterUrl"
      :search-results="suggestions"
      :search-results-label="$i18n('searchresults').text()"
      :show-thumbnail="showThumbnail"
      :title="searchTitle"
      :visible-item-limit="visibleItemLimit"
      @load-more="onLoadMore"
      @blur="isFocused = false"
      @focus="isFocused = true"
      @input="onInput"
  >
    <template #default>
      <input type="hidden" name="title" :value="searchPageTitle" />
<!--      <input type="hidden" name="wprov" :value="wprov" />-->
    </template>
    <template #search-results-pending>
      {{$i18n('idleon-search-loader').text()}}
    </template>
    <template #search-footer-text="{searchQuery}">
      <span v-i18n-html:idleon-search-footer="[ searchQuery ]"></span>
    </template>
  </cdx-typeahead-search>
</template>