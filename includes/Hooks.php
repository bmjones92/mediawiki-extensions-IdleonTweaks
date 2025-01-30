<?php

namespace MediaWiki\Extension\IdleonTweaks;

use OutputPage;
use Skin;

class Hooks {

	/**
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
        $out->addHTML("SERVER THEME: " . $theme);
    }

}
