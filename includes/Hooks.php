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
		$theme = $skin->getContext()->getRequest()->getCookie('theme', '');
		if ($theme) {
			$out->addBodyClasses('theme-' . $theme);
		}
	}

}
