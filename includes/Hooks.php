<?php

namespace MediaWiki\Extension\IdleonTweaks;

use OutputPage;
use Skin;

class Hooks {

	/**
     * Hook which is called before the page is displayed.
	 * @param $out OutputPage
	 * @param $skin Skin
	 * @return void
	 */
	public static function onBeforePageDisplay(OutputPage $out, Skin $skin): void {
        # Inject the theme cookie into the body class if one is present.
		$theme = $skin->getContext()->getRequest()->getCookie('theme', '');
		if ($theme !== null) {
			$out->addBodyClasses('theme-' . $theme);
		}
    }

    /**
     * Hook which is called when the skin is building the sidebar.
     * @param Skin $skin
     * @param $bar
     * @return void
     */
    public static function onSkinBuildSidebar(Skin $skin, &$bar): void {
        foreach ($bar as &$section) {
            foreach ($section as &$item) {
                # Items with empty page targets should be rendered as labels instead of links.
                if ($item['href'] === '') {
                    $item['class'] = 'sidebar-sub-section-label';
                    unset($item['href']);
                }
            }
        }
    }

}
