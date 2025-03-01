<?php
namespace MediaWiki\Extension\IdleonTweaks;

class Hooks {

    public static function onRegistration(): void {
        define("CONTENT_MODEL_LESS", "less");
    }

}