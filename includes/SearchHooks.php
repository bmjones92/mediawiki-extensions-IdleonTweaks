<?php
namespace MediaWiki\Extension\IdleonTweaks;

use MediaWiki\Output\Hook\BeforePageDisplayHook;
use MediaWiki\Output\OutputPage;
use Skin;

class SearchHooks implements BeforePageDisplayHook {

    /**
     * @param $out OutputPage
     * @param $skin Skin
     * @return void
     */
    public function onBeforePageDisplay($out, $skin): void {
        if ($out->getConfig()->get('IdleonEnableEnhancedSearch')) {
            $out->addModules('ext.idleon.search');
        }
    }

}