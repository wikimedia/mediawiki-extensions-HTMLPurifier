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

		// Set the config
		$config = HTMLPurifier_Config::createDefault();
		foreach ( $wgHTMLPurifierConfig as $key => $value ) {
			$config->set( $key, $value );
		}

		// Allow others to do more complex configurations
		Hooks::run( 'HTMLPurifierBeforePurify', [ &$config ] );

		// Purify the HTML
		$purifier = new HTMLPurifier( $config );
		$html = $purifier->purify( $input );

		// Allow others to further transform the purified HTML
		Hooks::run( 'HTMLPurifierAfterPurify', [ &$html, $purifier ] );

		return $html;
	}
}
