<?php
namespace MediaWiki\Extension\IdleonTweaks;

use MediaWiki\Hook\SkinBuildSidebarHook;
use MediaWiki\Output\Hook\BeforePageDisplayHook;
use MediaWiki\Output\OutputPage;
use Skin;

class ThemeHooks implements BeforePageDisplayHook, SkinBuildSidebarHook
{

    /**
     * @param $out OutputPage
     * @param $skin Skin
     * @return void
     */
    public function onBeforePageDisplay($out, $skin): void
    {
        $themeId = $skin->getRequest()->getCookie('theme','');
        if ($themeId) {
            $out->addBodyClasses('theme-' . $themeId);
        }
    }

    /**
     * @param $skin
     * @param $bar
     * @return void
     */
    public function onSkinBuildSidebar($skin, &$bar): void
    {
        foreach ($bar as &$section) {
            foreach ($section as &$item) {
                // Items with empty page targets should be rendered as labels instead of links.
                if ($item['href'] === '') {
                    $item['class'] = 'sidebar-sub-section-label';
                    unset($item['href']);
                }
            }
        }
    }

}