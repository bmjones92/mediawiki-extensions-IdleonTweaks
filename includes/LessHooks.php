<?php
namespace MediaWiki\Extension\IdleonTweaks;

use MediaWiki\Extension\CodeEditor\Hooks\CodeEditorGetPageLanguageHook;
use MediaWiki\Hook\ParserPreSaveTransformCompleteHook;
use MediaWiki\Revision\Hook\ContentHandlerDefaultModelForHook;
use MediaWiki\Title\Title;

class LessHooks implements
    CodeEditorGetPageLanguageHook,
    ContentHandlerDefaultModelForHook ,
    ParserPreSaveTransformCompleteHook
{

    const LESS_EXTENSION = ".less";

    public function onCodeEditorGetPageLanguage(Title $title, ?string &$lang, string $model, string $format)
    {
    }

    /**
     * @inheritDoc
     */
    public function onContentHandlerDefaultModelFor($title, &$model)
    {
        if ($title->getNamespace() === NS_MEDIAWIKI && str_ends_with($title, self::LESS_EXTENSION)) {
            $model = CONTENT_MODEL_LESS;
            return false;
        }
        return true;
    }

    public function onParserPreSaveTransformComplete($parser, &$text)
    {

    }

}