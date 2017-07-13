<?php

return [
	'name' => 'spqr/detectadblocker',
	'type' => 'extension',
	'main' => function( $app ) {
	
	},
	
	'autoload' => [
		'Spqr\\Detectadblocker\\' => 'src'
	],
	
	'nodes' => [],
	
	'routes' => [],
	
	'widgets' => [],
	
	'menu' => [],
	
	'permissions' => [
		'detectadblocker: manage settings' => [
			'title' => 'Manage settings'
		]
	],
	
	'settings' => 'detectadblocker-settings',
	
	'resources' => [
		'spqr/detectadblocker:' => ''
	],
	
	'config' => [
		'nodes'      => [],
		'html'       => '',
		'reset'      => true,
		'checktime'  => 50,
		'loopnumber' => 5,
		'baitclass'  => 'pub_300x250 pub_300x250m pub_728x90 text-ad textAd text_ad text_ads text-ads text-ad-links',
		'baitstyle'  => 'width: 1px !important; height: 1px !important; position: absolute !important; left: -10000px !important; top: -1000px !important;',
		'debug'      => false,
		'sticky'     => true
	],
	
	'events' => [
		
		'boot'         => function( $event, $app ) {
		},
		'site'         => function( $event, $app ) {
			$app->on(
				'view.content',
				function( $event, $scripts ) use ( $app ) {
					
					if ( ( !$this->config[ 'nodes' ] || in_array( $app[ 'node' ]->id, $this->config[ 'nodes' ] ) ) ) {
						$config = $this->config;
						
						$params      = [];
						$paramstring = '';
						
						$params[ 'resetOnEnd' ]    = ( !empty( $config[ 'reset' ] ) ? $config[ 'reset' ] : true );
						$params[ 'loopCheckTime' ] = ( !empty( $config[ 'checktime' ] ) ? $config[ 'checktime' ] : 0 );
						$params[ 'loopMaxNumber' ] =
							( !empty( $config[ 'loopnumber' ] ) ? $config[ 'loopnumber' ] : 0 );
						$params[ 'baitClass' ]     = ( !empty( $config[ 'baitclass' ] ) ? $config[ 'baitclass' ] : '' );
						$params[ 'baitStyle' ]     = ( !empty( $config[ 'baitstyle' ] ) ? $config[ 'baitstyle' ] : '' );
						$params[ 'debug' ]         = ( !empty( $config[ 'debug' ] ) ? $config[ 'debug' ] : false );
						
						foreach ( $params as $key => $param ) {
							if ( is_bool( $param ) ) {
								if ( $param === true )
									$paramstring .= "$key: true,";
								if ( $param === false )
									$paramstring .= "$key: false,";
							} elseif ( is_numeric( $param ) ) {
								$paramstring .= "$key: $param,";
							} else {
								$paramstring .= "$key: '$param',";
							}
						}
						
						$paramstring = rtrim( $paramstring, "," );
						$html        = preg_replace( "/\s+|\n+|\r/", ' ', $config[ 'html' ] );
						
						if ( $config[ 'sticky' ] == true ) {
							
							$script = " function adBlockDetected() {
									var s = '$html';
									var parser = new DOMParser();
									var doc = parser.parseFromString( s, 'text/html' );
									var detectionmodal = UIkit.modal.blockUI(doc.body.innerHTML);
					            }
								if(typeof blockAdBlock === 'undefined') {
									adBlockDetected();
								} else {
									blockAdBlock.onDetected(adBlockDetected);
								}
								
								blockAdBlock.setOption({
									$paramstring
								});";
						} else {
							$script = " function adBlockDetected() {
									var s = '$html';
									var parser = new DOMParser();
									var doc = parser.parseFromString( s, 'text/html' );
									var detectionmodal = UIkit.modal.alert(doc.body.innerHTML);
					            }
								if(typeof blockAdBlock === 'undefined') {
									adBlockDetected();
								} else {
									blockAdBlock.onDetected(adBlockDetected);
								}
								
								blockAdBlock.setOption({
									$paramstring
								});";
						}
						
						$app[ 'scripts' ]->add(
							'blockadblock',
							'spqr/detectadblocker:app/assets/blockadblock/blockadblock.js'
						);
						
						$app[ 'scripts' ]->add( 'detectadblocker', $script, [], 'string' );
					}
				}
			);
		},
		'view.scripts' => function( $event, $scripts ) use ( $app ) {
			$scripts->register(
				'toc-detectadblocker',
				'spqr/detectadblocker:app/bundle/detectadblocker-settings.js',
				[ '~extensions', 'input-tree', 'editor' ]
			);
		}
	]

];