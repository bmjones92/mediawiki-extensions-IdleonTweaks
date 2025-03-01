<?php
namespace MediaWiki\Extension\IdleonTweaks;

use Less_Exception_Compiler;
use Less_Exception_Parser;
use Less_Parser;
use MediaWiki\Content\CodeContentHandler;
use MediaWiki\Content\Content;
use MediaWiki\Content\CssContentHandler;
use MediaWiki\Content\JavaScriptContentHandler;
use MediaWiki\Content\JsonContentHandler;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\Content\ValidationParams;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use StatusValue;
use WikiPage;

class LessCssContentHandler extends CodeContentHandler {

    /**
     * Extension for LessCSS pages.
     */
    private const LESS_MODEL_EXTENSION = "less";

    public function __construct($modelId = CONTENT_MODEL_LESS, $formats = [CONTENT_FORMAT_TEXT])
    {
        parent::__construct($modelId, $formats);
    }

    /**
     * @inheritDoc
     */
    protected function getContentClass(): string
    {
        return LessCsscontentHandler::class;
    }

    /**
     * @param LessCssContent $content
     * @param ValidationParams $validationParams
     * @return StatusValue
     */
    public function validateSave(
        Content $content,
        ValidationParams $validationParams
    ) {
        $status = parent::validateSave($content, $validationParams);
        if (!$status->isGood() || !($content instanceof LessCssContent)) {
            return $status;
        }

        try {
            $parser = new Less_Parser();
            $parser->parse($content->getText());
            return StatusValue::newGood();
        } catch (Less_Exception_Parser $ex) {
            return StatusValue::newFatal($ex->getMessage());
        }
    }

    /**
     * @param LessCssContent $content
     * @param ContentParseParams $cpoParams
     * @param ParserOutput $output
     * @return void
     */
    protected function fillParserOutput(
        Content $content,
        ContentParseParams $cpoParams,
        ParserOutput &$output
    ): void {
        $textModelsToParse = MediaWikiServices::getInstance()->getMainConfig()->get(MainConfigNames::TextModelsToParse);
        if (in_array($content->getModel(), $textModelsToParse)) {

            $parserOptions = WikiPage::makeParserOptionsFromTitleAndModel(
                $cpoParams->getPage(),
                $content->getModel(),
                'canonical'
            );

            $parser = MediaWikiServices::getInstance()->getParserFactory()->getInstance();
            $output = $parser->parse(
                $content->getText(),
                $cpoParams->getPage(),
                $parserOptions,
                true,
                true,
                $cpoParams->getRevId()
            );
        }

        if ($cpoParams->getGenerateHtml()) {
            $html = Html::element("pre", [
                "class" => "mw-code mw-less-css",
                "dir" => "ltr"
            ], "\n" . $content->getText() . "\n") . "\n";
        } else {
            $html = null;
        }

        $output->clearWrapperDivClass();
        $output->setRawText($html);
        $output->setOutputFlag(ParserOutputFlags::NO_TOC);
        $output->setSections([]);
    }

}