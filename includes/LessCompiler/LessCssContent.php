<?php
namespace MediaWiki\Extension\IdleonTweaks;

use MediaWiki\Content\TextContent;

/**
 * Content type for LessCSS pages.
 */
class LessCssContent extends TextContent {

    public function __construct($text, $model = CONTENT_MODEL_LESS)
    {
        parent::__construct($text, $model);
    }

}