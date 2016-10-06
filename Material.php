<?php

if ( function_exists( 'wfLoadSkin' ) ) {
	wfLoadSkin( 'Material' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['Material'] = __DIR__ . '/i18n';
	return true;
} else {
	die( 'This version of the Material skin requires MediaWiki 1.25+' );
}
