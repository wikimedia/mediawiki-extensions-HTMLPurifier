<?php

class HTMLPurifierHooks {

	/**
	 * @param Parser $parser
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setHook( 'html', [ self::class, 'onHTML' ] );
	}

	/**
	 * @param string $input Dirty HTML
	 * @return Purified HTML
	 */
	public static function onHTML( $input ) {
		global $wgHTMLPurifierConfig;
		$config = HTMLPurifier_Config::createDefault();
		foreach ( $wgHTMLPurifierConfig as $key => $value ) {
			$config->set( $key, $value );
		}
		$purifier = new HTMLPurifier( $config );
		return $purifier->purify( $input );
	}
}
