<?php
/**
 * SkinTemplate class for Material skin
 * @ingroup Skins
 */
class SkinMaterial extends SkinTemplate {
	public $skinname = 'material';
	public $stylename = 'Material';
	public $template = 'MaterialTemplate';
	/**
	 * @var Config
	 */
	private $materialConfig;

	public function __construct() {
		$this->materialConfig = ConfigFactory::getDefaultInstance()->makeConfig( 'material' );
	}

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param OutputPage $out Object to initialize
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		$out->addModules( 'skins.material.js' );
	}

	/**
	 * Loads skin and user CSS files.
	 * @param OutputPage $out
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		$styles = [ 'mediawiki.skinning.interface', 'skins.material.styles' ];
		Hooks::run( 'SkinMaterialStyleModules', [ $this, &$styles ] );
		$out->addModuleStyles( $styles );
	}

	/**
	 * Override to pass our Config instance to it
	 */
	public function setupTemplate( $classname, $repository = false, $cache_dir = false ) {
		return new $classname( $this->materialConfig );
	}
}
