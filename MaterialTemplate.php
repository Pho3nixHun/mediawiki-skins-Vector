<?php

class MaterialTemplate extends BaseTemplate {

	public function execute() {
		
		$nav = $this->data['content_navigation'];

		$xmlID = '';
		foreach ( $nav as $section => $links ) {
			foreach ( $links as $key => $link ) {
				if ( $section == 'views' && !( isset( $link['primary'] ) && $link['primary'] ) ) {
					$link['class'] = rtrim( 'collapsible ' . $link['class'], ' ' );
				}

				$xmlID = isset( $link['id'] ) ? $link['id'] : 'ca-' . $xmlID;
				$nav[$section][$key]['attributes'] =
					' id="' . Sanitizer::escapeId( $xmlID ) . '"';
				if ( $link['class'] ) {
					$nav[$section][$key]['attributes'] .=
						' class="' . htmlspecialchars( $link['class'] ) . '"';
					unset( $nav[$section][$key]['class'] );
				}
				if ( isset( $link['tooltiponly'] ) && $link['tooltiponly'] ) {
					$nav[$section][$key]['key'] =
						Linker::tooltip( $xmlID );
				} else {
					$nav[$section][$key]['key'] =
						Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $xmlID ) );
				}
			}
		}
		$this->data['namespace_urls'] = $nav['namespaces'];
		$this->data['view_urls'] = $nav['views'];
		$this->data['action_urls'] = $nav['actions'];
		$this->data['variant_urls'] = $nav['variants'];

		$this->data['pageLanguage'] =
			$this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();

		$this->html( 'headelement' );
		?>
        <div id="wrapper" class="wrapper" layout="column" ng-controller="indexCtrl" ng-cloak>
            <md-toolbar md-whiteframe="3" class="md-menu-toolbar" id="header">
				<div layout="row">
					<md-toolbar-filler layout layout-align="center center" md-colors="accent">
						<md-button class="md-icon-button" ng-click="toggleSideNav()">
							<md-icon md-font-set="material-icons">menu</md-icon>
						</md-button>
					</md-toolbar-filler>
					<div layout="row" flex>
						<div>
							<h2 class="md-toolbar-tools"> <a ng-click="clickHandler({href:'<?php
							echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] )
							?>'})"> {{sitename}} </a></h2>
							<md-menu-bar>
								<md-menu ng-repeat="menu in headerMenus">
									<button ng-click="$mdOpenMenu()">
										{{menu.text}}
									</button>
									<md-menu-content>
										<md-menu-item ng-repeat="menuitem in menu.items">
											<md-button ng-click="clickHandler(menuitem)">
												<md-icon md-font-set="material-icons">{{menuitem.icon}}</md-icon>
												{{menuitem.text}}
											</md-button>
										</md-menu-item>	
									</md-menu-content>
								</md-menu>
							</md-menu-bar>
						</div>
						<form ng-submit='search.onEnter(search.lastResult)' layout-align="center center" layout="row" flex>
							<md-autocomplete flex=80 ng-disabled="search.isDisabled" md-no-cache="search.noCache" md-selected-item="search.selectedItem" md-search-text="search.searchText" md-selected-item-change="search.selectedItemChange(item)"
							md-items="item in search.querySearch(search.searchText)" md-item-text="item.text" md-min-length="0" placeholder="Looking for an artice?" ng-focus="quote = randomQuote()">
								<md-item-template>
									<span md-highlight-text="search.searchText" md-highlight-flags="^i">{{item.text}}</span>
								</md-item-template>
								<md-not-found>
									<span ng-show="search.searchText">
										No articles matching "{{search.searchText}}" were found.
										<a ng-click="search.createNew(search.searchText)">Create a new one!</a>
										or try on 
										<a href="https://www.google.hu/search?q={{search.searchText}}">Google</a>
									</span>
									<span ng-show="!search.searchText">
										<i>{{quote}}</i>
									</span>
								</md-not-found>
							</md-autocomplete>
							<md-button class="md-icon-button" ng-click='goToPage("/index.php?search=" + search.searchText + "&title=Special%3ASearch&fulltext=Search")'>
								<md-icon md-font-set="material-icons">search</md-icon>
							</md-button>
						</form>
					</div>
				</div>
			</md-toolbar>
			<div id="contentWrapper" flex>
				<div layout="column" layout-align="center center" ng-show="search.isLoading" layout-fill>
					<div flex></div>
					<md-progress-circular md-mode="indeterminate" ></md-progress-circular>
					<div flex></div>
				</div>
				<section ng-hide="search.isLoading" id="content" role="main" flex layout-padding >
					<a id="top"></a>
					<?php
					if ( $this->data['sitenotice'] ) {
						?>
						<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
					<?php
					}
					?>
					<?php
					if ( is_callable( [ $this, 'getIndicators' ] ) ) {
						echo $this->getIndicators();
					}

					if ( $this->data['title'] != '' ) {
					?>
					<h1 id="firstHeading" class="firstHeading" lang="<?php $this->text( 'pageLanguage' ); ?>"><?php
						$this->html( 'title' )
					?></h1>
					<?php
					} ?>
					<?php $this->html( 'prebodyhtml' ) ?>
					<div id="bodyContent" class="mw-body-content">
						<?php
						if ( $this->data['isarticle'] ) {
							?>
							<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
						<?php
						}
						?>
						<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php
							$this->html( 'subtitle' )
						?></div>
						<?php
						if ( $this->data['undelete'] ) {
							?>
							<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
						<?php
						}
						?>
						<?php
						if ( $this->data['newtalk'] ) {
							?>
							<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
						<?php
						}
						?>
						<div id="jump-to-nav" class="mw-jump">
							<?php $this->msg( 'jumpto' ) ?>
							<a href="#mw-head"><?php
								$this->msg( 'jumptonavigation' )
							?></a><?php $this->msg( 'comma-separator' ) ?>
							<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
						</div>
						<?php
						$this->html( 'bodycontent' );

						if ( $this->data['printfooter'] ) {
							?>
							<div class="printfooter">
								<?php $this->html( 'printfooter' ); ?>
							</div>
						<?php
						}

						if ( $this->data['catlinks'] ) {
							$this->html( 'catlinks' );
						}

						if ( $this->data['dataAfterContent'] ) {
							$this->html( 'dataAfterContent' );
						}
						?>
						<div class="visualClear"></div>
						<?php $this->html( 'debughtml' ); ?>
					</div>
				</section>
			</div>
			<footer id="footer"> </footer>
			<md-sidenav class="md-sidenav-left" md-component-id="left" md-whiteframe="4">
				<md-toolbar layout=row layout-align="center center" style="min-height: 96px">
				<md-toolbar-filler layout layout-align="center center" md-colors="accent">
					<md-button class="md-icon-button" ng-click="toggleSideNav()">
						<md-icon md-font-set="material-icons">chevron_left</md-icon>
					</md-button>
				</md-toolbar-filler>
				<h2 class="md-toolbar-tools"><?php $this->msg( 'navigation-heading' ) ?></h2>
				</md-toolbar>
				<md-list>
				<?php # $this->renderPortals( $this->data['sidebar'] ); ?>
					<div ng-repeat="(key, value) in sidebarGroups">
						<md-subheader class="md-no-sticky" ng-click="value.isClosed = !value.isClosed">
						<md-icon md-font-set="material-icons">expand_more</md-icon>
						{{key}}
						</md-subheader>
						<md-list-item class="secondary-button-padding" ng-repeat="item in value" ng-click="clickHandler(item) || toggleSideNav()" ng-hide="value.isClosed">
							<p class="md-body-1 side-menu-item">{{item.text}}</p>
						</md-list-item>
					</div>
				</md-list>
			</md-sidenav>
        </div>
		
		<?php $this->printTrail(); ?>
		<script>
			_mwSkinData = <?php echo json_encode($this->data) ?>;
		</script>
	</body>
</html>
<?php
	}

	/**
	 * Render a series of portals
	 *
	 * @param array $portals
	 */
	protected function renderPortals( $portals ) {
		// Force the rendering of the following portals
		if ( !isset( $portals['SEARCH'] ) ) {
			$portals['SEARCH'] = true;
		}
		if ( !isset( $portals['TOOLBOX'] ) ) {
			$portals['TOOLBOX'] = true;
		}
		if ( !isset( $portals['LANGUAGES'] ) ) {
			$portals['LANGUAGES'] = true;
		}
		// Render portals
		foreach ( $portals as $name => $content ) {
			if ( $content === false ) {
				continue;
			}

			// Numeric strings gets an integer when set as key, cast back - T73639
			$name = (string)$name;

			switch ( $name ) {
				case 'SEARCH':
					break;
				case 'TOOLBOX':
					$this->renderPortal( 'tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' );
					break;
				case 'LANGUAGES':
					if ( $this->data['language_urls'] !== false ) {
						$this->renderPortal( 'lang', $this->data['language_urls'], 'otherlanguages' );
					}
					break;
				default:
					$this->renderPortal( $name, $content );
					break;
			}
		}
	}

	/**
	 * @param string $name
	 * @param array $content
	 * @param null|string $msg
	 * @param null|string|array $hook
	 */
	protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
		if ( $msg === null ) {
			$msg = $name;
		}
		$msgObj = wfMessage( $msg );
		$labelId = Sanitizer::escapeId( "p-$name-label" );
		?>
		<div class="portal" role="navigation" id='<?php
		echo Sanitizer::escapeId( "p-$name" )
		?>'<?php
		echo Linker::tooltip( 'p-' . $name )
		?> aria-labelledby='<?php echo $labelId ?>'>
			<h3<?php $this->html( 'userlangattributes' ) ?> id='<?php echo $labelId ?>'><?php
				echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg );
				?></h3>

			<div class="body">
				<?php
				if ( is_array( $content ) ) {
					?>
					<ul>
						<?php
						foreach ( $content as $key => $val ) {
							echo $this->makeListItem( $key, $val );
						}
						if ( $hook !== null ) {
							Hooks::run( $hook, [ &$this, true ] );
						}
						?>
					</ul>
				<?php
				} else {
					echo $content; /* Allow raw HTML block to be defined by extensions */
				}

				$this->renderAfterPortlet( $name );
				?>
			</div>
		</div>
	<?php
	}

	/**
	 * Render one or more navigations elements by name, automatically reveresed
	 * when UI is in RTL mode
	 *
	 * @param array $elements
	 */
	protected function renderNavigation( $elements ) {
		// If only one element was given, wrap it in an array, allowing more
		// flexible arguments
		if ( !is_array( $elements ) ) {
			$elements = [ $elements ];
			// If there's a series of elements, reverse them when in RTL mode
		} elseif ( $this->data['rtl'] ) {
			$elements = array_reverse( $elements );
		}
		// Render elements
		foreach ( $elements as $name => $element ) {
			switch ( $element ) {
				case 'NAMESPACES':
					?>
					<md-menu>
						<button ng-click="$mdOpenMenu()">
						Page
						</button>
						<md-menu-content>
							<?php foreach ( $this->data['namespace_urls'] as $link ) { ?>
							<md-menu-item>
								<md-button ng-click="click('<?php echo htmlspecialchars( $link['href'] ) ?>')">
									<?php echo htmlspecialchars( $link['text'] ) ?>
								</md-button>
							</md-menu-item>
							<?php } ?>
						</md-menu-content>
					</md-menu>
					<div id="p-namespaces" role="navigation" class="vectorTabs<?php
					if ( count( $this->data['namespace_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-namespaces-label">
						<h3 id="p-namespaces-label"><?php $this->msg( 'namespaces' ) ?></h3>
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php
							foreach ( $this->data['namespace_urls'] as $link ) {
								?>
								<li <?php echo $link['attributes'] ?>><span><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key'];
										if ( isset ( $link['rel'] ) ) {
											echo ' rel="' . htmlspecialchars( $link['rel'] ) . '"';
										}
										?>><?php
											echo htmlspecialchars( $link['text'] )
											?></a></span></li>
							<?php
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'VARIANTS':
					?>
					<div id="p-variants" role="navigation" class="vectorMenu<?php
					if ( count( $this->data['variant_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-variants-label">
						<?php
						// Replace the label with the name of currently chosen variant, if any
						$variantLabel = $this->getMsg( 'variants' )->text();
						foreach ( $this->data['variant_urls'] as $link ) {
							if ( stripos( $link['attributes'], 'selected' ) !== false ) {
								$variantLabel = $link['text'];
								break;
							}
						}
						?>
						<h3 id="p-variants-label">
							<span><?php echo htmlspecialchars( $variantLabel ) ?></span><a href="#"></a>
						</h3>

						<div class="menu">
							<ul>
								<?php
								foreach ( $this->data['variant_urls'] as $link ) {
									?>
									<li<?php echo $link['attributes'] ?>><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" lang="<?php
										echo htmlspecialchars( $link['lang'] )
										?>" hreflang="<?php
										echo htmlspecialchars( $link['hreflang'] )
										?>" <?php
										echo $link['key']
										?>><?php
											echo htmlspecialchars( $link['text'] )
											?></a></li>
								<?php
								}
								?>
							</ul>
						</div>
					</div>
					<?php
					break;
				case 'VIEWS':
					?>
					<div id="p-views" role="navigation" class="vectorTabs<?php
					if ( count( $this->data['view_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-views-label">
						<h3 id="p-views-label"><?php $this->msg( 'views' ) ?></h3>
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php
							foreach ( $this->data['view_urls'] as $link ) {
								?>
								<li<?php echo $link['attributes'] ?>><span><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key'];
										if ( isset ( $link['rel'] ) ) {
											echo ' rel="' . htmlspecialchars( $link['rel'] ) . '"';
										}
										?>><?php
											// $link['text'] can be undefined - bug 27764
											if ( array_key_exists( 'text', $link ) ) {
												echo array_key_exists( 'img', $link )
													? '<img src="' . $link['img'] . '" alt="' . $link['text'] . '" />'
													: htmlspecialchars( $link['text'] );
											}
											?></a></span></li>
							<?php
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'ACTIONS':
					?>
					<div id="p-cactions" role="navigation" class="vectorMenu<?php
					if ( count( $this->data['action_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-cactions-label">
						<h3 id="p-cactions-label"><span><?php
							$this->msg( 'vector-more-actions' )
						?></span><a href="#"></a></h3>

						<div class="menu">
							<ul<?php $this->html( 'userlangattributes' ) ?>>
								<?php
								foreach ( $this->data['action_urls'] as $link ) {
									?>
									<li<?php echo $link['attributes'] ?>>
										<a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] )
											?></a>
									</li>
								<?php
								}
								?>
							</ul>
						</div>
					</div>
					<?php
					break;
				case 'PERSONAL':
					?>
					<div id="p-personal" role="navigation" class="<?php
					if ( count( $this->data['personal_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-personal-label">
						<h3 id="p-personal-label"><?php $this->msg( 'personaltools' ) ?></h3>
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php

							$notLoggedIn = '';

							if ( !$this->getSkin()->getUser()->isLoggedIn() &&
								User::groupHasPermission( '*', 'edit' ) ){

								$notLoggedIn =
									Html::rawElement( 'li',
										[ 'id' => 'pt-anonuserpage' ],
										$this->getMsg( 'notloggedin' )->escaped()
									);

							}

							$personalTools = $this->getPersonalTools();

							$langSelector = '';
							if ( array_key_exists( 'uls', $personalTools ) ) {
								$langSelector = $this->makeListItem( 'uls', $personalTools[ 'uls' ] );
								unset( $personalTools[ 'uls' ] );
							}

							if ( !$this->data[ 'rtl' ] ) {
								echo $langSelector;
								echo $notLoggedIn;
							}

							foreach ( $personalTools as $key => $item ) {
								echo $this->makeListItem( $key, $item );
							}

							if ( $this->data[ 'rtl' ] ) {
								echo $notLoggedIn;
								echo $langSelector;
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'SEARCH':
					?>
					<div id="p-search" role="search">
						<h3<?php $this->html( 'userlangattributes' ) ?>>
							<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
						</h3>

						<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
							<div<?php echo $this->config->get( 'VectorUseSimpleSearch' ) ? ' id="simpleSearch"' : '' ?>>
							<?php
							echo $this->makeSearchInput( [ 'id' => 'searchInput' ] );
							echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
							echo $this->makeSearchButton(
								'fulltext',
								[ 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' ]
							);
							echo $this->makeSearchButton(
								'go',
								[ 'id' => 'searchButton', 'class' => 'searchButton' ]
							);
							?>
							</div>
						</form>
					</div>
					<?php

					break;
			}
		}
	}
}
