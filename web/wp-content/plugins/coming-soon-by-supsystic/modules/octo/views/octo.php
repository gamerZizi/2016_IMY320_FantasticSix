<?php
class octoViewScs extends viewScs {
	protected $_twig;
	public function getTabContent() {
		frameScs::_()->getModule('templates')->loadJqGrid();
		frameScs::_()->addStyle('admin.octo', $this->getModule()->getModPath(). 'css/admin.octo.css');
		frameScs::_()->addScript('admin.octo', $this->getModule()->getModPath(). 'js/admin.octo.js');
		frameScs::_()->addScript('admin.octo.list', $this->getModule()->getModPath(). 'js/admin.octo.list.js');
		frameScs::_()->addJSVar('admin.octo.list', 'octTblDataUrl', uriScs::mod('octo', 'getListForTbl', array('reqType' => 'ajax')));
		
		//$this->assign('addNewLink', frameScs::_()->getModule('options')->getTabUrl('SCS_add_new'));
		return parent::getContent('octoAdmin');
	}
	public function showMainMetaBox($post) {
		frameScs::_()->getModule('templates')->loadCoreJs();
		frameScs::_()->getModule('templates')->loadAdminCoreJs();
		frameScs::_()->addScript('admin.octo.post', $this->getModule()->getModPath(). 'js/admin.octo.post.js');
		frameScs::_()->addStyle('admin.octo.post', $this->getModule()->getModPath(). 'css/admin.octo.post.css');
		frameScs::_()->addStyle('frontend.octo.editor.octo-icons', $this->getModule()->getAssetsUrl(). 'css/octo-icons.css');
		$this->assign('isPostConverted', $this->getModel()->isPostConverted( $post->ID ));
		$this->assign('post', $post);
		$this->assign('usedBlocksNumber', $this->getModel()->getUsedBlocksNumForPost( $post->ID ));
		parent::display('octoMainMetaBox');
	}
	public function renderForPost($oid, $params = array()) {
		//frameScs::_()->setStylesInitialized(false);
		//frameScs::_()->setScriptsInitialized(false);
		$isEditMode = isset($params['isEditMode']) ? $params['isEditMode'] : false;
		$isPreviewMode = isset($params['isPreviewMode']) ? $params['isPreviewMode'] : false;

		add_action('wp_enqueue_scripts', array($this, 'filterScripts'));
		add_action('wp_print_styles', array($this, 'filterStyles'));
		//$post = isset($params['post']) ? $params['post'] : get_post($pid);
		$octo = $this->getModel()->getFullById($oid);
		if($isEditMode) {
			$this->loadWpAdminAssets();
		}
		frameScs::_()->getModule('templates')->loadCoreJs();
		frameScs::_()->getModule('templates')->loadBootstrap();
		frameScs::_()->getModule('templates')->loadCustomBootstrapColorpicker();
		$this->connectFrontendAssets( $octo, $isEditMode, $isPreviewMode );
		if($isEditMode) {
			$originalBlocksByCategories = $this->getModel('octo_blocks')->getOriginalBlocksByCategories();
			$this->assign('originalBlocksByCategories', $originalBlocksByCategories);
			$this->connectEditorAssets( $octo, $isPreviewMode );
			$this->assign('allPagesUrl', frameScs::_()->getModule('options')->getTabUrl('settings'));
			$this->assign('noImgUrl', $this->getModule()->getAssetsUrl(). 'img/no-photo.png');
			//$this->assign('previewPageUrl', get_permalink($post));
		}
		$this->_prepareScsoForRender( $octo, $isEditMode );
		
		$this->assign('mobileDetect', new Mobile_Detect());

		$this->assign('octo', $octo);
		//$this->assign('pid', $pid);
		$this->assign('isEditMode', $isEditMode);
		//$this->assign('post', $post);
		$this->assign('stylesScriptsHtml', $this->generateStylesScriptsHtml());
		// Render this part - at final step
		$this->assign('commonFooter', $this->getCommonFooter());
		if($isEditMode) {
			$this->assign('editorFooter', $this->getEditorFooter());
		} else {
			$this->assign('footer', $this->getFooter());
		}
		parent::display('octoRenderForPost');
	}
	public function getEditorFooter() {
		return parent::getContent('octoEditorFooter');
	}
	public function getFooter() {
		return parent::getContent('octoFooter');
	}
	// Footer parts that need to be in frontend and in editor too
	public function getCommonFooter() {
		return parent::getContent('octoCommonFooter');
	}
	private function _prepareScsoForRender(&$octo, $isEditMode = false) {
		if(!empty($octo['blocks'])) {
			foreach($octo['blocks'] as $i => $block) {
				$octo['blocks'][ $i ]['rendered_html'] = $this->renderBlock( $octo['blocks'][ $i ], $isEditMode );
				if(!$isEditMode) {
					$octo['blocks'][ $i ]['rendered_html'] = do_shortcode( $octo['blocks'][ $i ]['rendered_html'] );
			}
		}
	}
	}
	public function renderBlock($block = array(), $isEditMode = false) {
		$this->assign('block', $block);
		$this->assign('isEditMode', $isEditMode);
		$content = parent::getContent('octoRenderBlock');
		$this->_initTwig();
		return $this->_twig->render($content, array('block' => $block));
	}
	public function connectFrontendAssets( $octo = array(), $isEditMode = false, $isPreviewMode = false ) {
		frameScs::_()->getModule('templates')->loadFontAwesome();
		frameScs::_()->addStyle('animate', $this->getModule()->getAssetsUrl(). 'css/animate.css');
		frameScs::_()->addStyle('frontend.octo.fonts', $this->getModule()->getAssetsUrl(). 'css/frontend.octo.fonts.css');
		frameScs::_()->addStyle('frontend.octo', $this->getModule()->getModPath(). 'css/frontend.octo.css');
		
		frameScs::_()->addStyle('slider.bx', $this->getModule()->getModPath(). 'assets/sliders/bx/jquery.bxslider.css');
		frameScs::_()->addScript('slider.bx', $this->getModule()->getModPath(). 'assets/sliders/bx/jquery.bxslider.min.js');

		frameScs::_()->addScript('frontend.octo.canvas', $this->getModule()->getModPath(). 'js/frontend.octo.canvas.js');
		frameScs::_()->addScript('frontend.octo.editor.blocks_fabric.base', $this->getModule()->getModPath(). 'js/frontend.octo.editor.blocks_fabric.base.js');
		frameScs::_()->addScript('frontend.octo.editor.blocks.base', $this->getModule()->getModPath(). 'js/frontend.octo.editor.blocks.base.js');
		frameScs::_()->addScript('frontend.octo.editor.elements.base', $this->getModule()->getModPath(). 'js/frontend.octo.editor.elements.base.js');
		
		frameScs::_()->addScript('frontend.octo', $this->getModule()->getModPath(). 'js/frontend.octo.js');

		$octo['time'] = getdate(current_time('timestamp'));
		$octo['isPreviewMode'] = $isPreviewMode;

		frameScs::_()->addJSVar('frontend.octo', 'scsOcto', $octo);
		
		frameScs::_()->getModule('templates')->loadLightbox();
	}
	public function connectEditorAssets( $octo = array() ) {
		$this->assign('adminEmail', get_bloginfo('admin_email'));
		$this->connectEditorJs( $octo );
		$this->connectEditorCss( $octo );
	}
	public function connectEditorJs( $octo = array() ) {
		frameScs::_()->addScript('jquery-ui-core');
		frameScs::_()->addScript('jquery-ui-widget');
		frameScs::_()->addScript('jquery-ui-mouse');
		
		frameScs::_()->addScript('jquery-ui-draggable');
		frameScs::_()->addScript('jquery-ui-sortable');
		//frameScs::_()->addScript('jquery-ui-dialog');
		
		frameScs::_()->getModule('templates')->loadMediaScripts();
		frameScs::_()->getModule('templates')->loadCustomBootstrapColorpicker();
		frameScs::_()->getModule('templates')->loadTinyMce();
		frameScs::_()->getModule('templates')->loadContextMenu();
		//frameScs::_()->getModule('templates')->loadCustomColorpicker();
		
		frameScs::_()->addScript('twig', SCS_JS_PATH. 'twig.min.js');
		frameScs::_()->addScript('icheck', SCS_JS_PATH. 'icheck.min.js');
		frameScs::_()->getModule('templates')->loadSlimscroll();
		frameScs::_()->addScript('frontend.octo.editor.menus', $this->getModule()->getModPath(). 'js/frontend.octo.editor.menus.js');
		frameScs::_()->addScript('wp.tabs', SCS_JS_PATH. 'wp.tabs.js');

		
		frameScs::_()->addScript('frontend.octo.editor.maintoolbar', $this->getModule()->getModPath(). 'js/frontend.octo.editor.maintoolbar.js');
		frameScs::_()->addScript('frontend.octo.editor.utils', $this->getModule()->getModPath(). 'js/frontend.octo.editor.utils.js');
		frameScs::_()->addScript('frontend.octo.editor.blocks_fabric', $this->getModule()->getModPath(). 'js/frontend.octo.editor.blocks_fabric.js');
		frameScs::_()->addScript('frontend.octo.editor.elements', $this->getModule()->getModPath(). 'js/frontend.octo.editor.elements.js');
		frameScs::_()->addScript('frontend.octo.editor.elements.menu', $this->getModule()->getModPath(). 'js/frontend.octo.editor.elements.menu.js');
		frameScs::_()->addScript('frontend.octo.editor.blocks', $this->getModule()->getModPath(). 'js/frontend.octo.editor.blocks.js');
		frameScs::_()->addScript('frontend.octo.editor', $this->getModule()->getModPath(). 'js/frontend.octo.editor.js');
		//frameScs::_()->addJSVar('frontend.octo.editor', 'octScso', $octo);
		frameScs::_()->getModule('templates')->loadChosenSelects();
		frameScs::_()->getModule('templates')->loadDatePicker();
		frameScs::_()->getModule('templates')->loadDateTimePicker();
		frameScs::_()->getModule('templates')->loadJqueryUi();
		frameScs::_()->getModule('templates')->loadTooltipstered();

		$scsEditor = array();
		$scsEditor['posts'] = array();

		$allPosts = array_merge(
			get_posts(
				array(
					'numberposts' => -1
				)
			), get_pages(
				array(
					'numberposts' => -1
				)
			)
		);

		if ($allPosts)
			foreach ($allPosts as $post)
				$scsEditor['posts'][] = array(
					'url' => get_permalink($post->ID),
					'title' => $post->post_title
				);

		frameScs::_()->addJSVar('frontend.octo.editor', 'scsEditor', $scsEditor);
	}
	public function connectEditorCss( $octo = array() ) {
		// We will use other instance of this lib here - to use prev. one in admin area
		frameScs::_()->addStyle('octo.jquery.icheck', $this->getModule()->getModPath(). 'css/jquery.icheck.css');
		frameScs::_()->addStyle('frontend.octo.editor', $this->getModule()->getModPath(). 'css/frontend.octo.editor.css');
		frameScs::_()->addStyle('frontend.octo.editor.tinymce', $this->getModule()->getModPath(). 'css/frontend.octo.editor.tinymce.css');
		frameScs::_()->addStyle('frontend.octo.editor.octo-icons', $this->getModule()->getAssetsUrl(). 'css/octo-icons.css');
		frameScs::_()->addStyle('supsystic-uiScs', SCS_CSS_PATH. 'supsystic-ui.css');
	}
	public function loadWpAdminAssets() {
		frameScs::_()->addStyle('wp.common', get_admin_url(). 'css/common.css');
	}
	public function generateWpScriptsStyles() {
		global $wp_scripts, $wp_styles;
		if(!$wp_scripts && !$wp_styles) return '';
		$this->assign('wpScripts', $wp_scripts);
		$this->assign('wpStyles', $wp_styles);
		return parent::getContent('octoWpScripts');
	}
	public function filterScripts() {
		global $wp_scripts;

		if (! $wp_scripts) return;

		$scripts = array();

		foreach ($wp_scripts->registered as $script) {
			if (strpos($script->src, '/wp-content/themes') === false) {
				$scripts[] = $script;
			}
		} 

		$wp_scripts->registered = $scripts;
	}
	public function filterStyles() {
		global $wp_styles;

		if (! $wp_styles) return;

		$styles = array();

		foreach ($wp_styles->registered as $style) {
			if (strpos($style->src, '/wp-content/themes') === false) {
				$styles[] = $style;
			}
		} 

		$wp_styles->registered = $styles;
	}
	public function generateStylesScriptsHtml() {
		if(version_compare(get_bloginfo('version'), '4.2.0', '<')) {
			global $wp_scripts;
			if ( ! ( $wp_scripts instanceof WP_Scripts ) ) {
				$wp_scripts = new WP_Scripts();
			}
		} else {
			$wp_scripts = wp_scripts();
		}
		$sufix = SCRIPT_DEBUG ? '' : '.min';
		$res = array();
		$res[] = $this->generateWpScriptsStyles();
		$styles = frameScs::_()->getStyles();
		if(!empty($styles)) {
			$usedHandles = array();
			$rel = 'stylesheet';
			$media = 'all';
			foreach($styles as $s) {
				if(!isset($usedHandles[ $s['handle'] ])) {
					$handle = $s['handle'];
					// TODO: add default wp src here - to search it by handles
					$rtl_href = isset($s['src']) ? $s['src'] : '';
					$res[] = "<link rel='$rel' id='$handle-rtl-css' href='$rtl_href' type='text/css' media='$media' />";
					$usedHandles[ $s['handle'] ] = 1;
				}
			}
		}
		$jsVars = frameScs::_()->getJSVars();
		if(!empty($jsVars)) {
			$res[] = "<script type='text/javascript'>"; // CDATA and type='text/javascript' is not needed for HTML 5
			$res[] = "/* <![CDATA[ */";
			foreach($jsVars as $scriptH => $vars) {
				foreach($vars as $name => $value) {
					if($name == 'dataNoJson' && !is_array($value)) {
						$res[] = $value;
					} else {
						$res[] = "var $name = ". utilsScs::jsonEncode($value). ";";
					}
				}
			}
			$res[] = "/* ]]> */";
			$res[] = "</script>";
		}
		$scripts = frameScs::_()->getScripts();
		if(!empty($scripts)) {
			$usedHandles = array();
			$includesUrl = includes_url();
			foreach($scripts as $s) {
				if(!isset($usedHandles[ $s['handle'] ])) {
					$handle = $s['handle'];
					$src = isset($s['src']) ? $s['src'] : '';
					if(empty($src)) {
						if($handle == 'jquery') {
							$src = $includesUrl. 'js/jquery/jquery.js';
						} else {
							if(strpos($handle, 'jquery-ui') === 0) {
								$src = $includesUrl. 'js/'. str_replace('-', '/', $handle). '.js';
							}
							if(!empty($sufix)) {
								$src = str_replace('.js', $sufix. '.js', $src);
							}
						}
						$wp_scripts->done[] = $handle;
					}
					$res[] = "<script type='text/javascript' src='$src'></script>";
					$usedHandles[ $s['handle'] ] = 1;
				}
			}
		}
		return implode(SCS_EOL, $res);
	}
	protected function _initTwig() {
		if(!$this->_twig) {
			if(!class_exists('Twig_Autoloader')) {
				require_once(SCS_CLASSES_DIR. 'Twig'. DS. 'Autoloader.php');
			}
			Twig_Autoloader::register();
			$this->_twig = new Twig_Environment(new Twig_Loader_String(), array('debug' => 1));
			$this->_twig->addFunction(
				new Twig_SimpleFunction('adjBs'	/*adjustBrightness*/, array(
						$this,
						'adjustBrightness'
					)
				)
			);
			$this->_twig->addFunction(
				new Twig_SimpleFunction('hexToRgb', array(
						$this,
						'hexToRgb'
					)
				)
			);
		}
	}
	public function hexToRgb($string, $alpha = false) {
		if(strpos($string, 'rgb') !== false)
			return $string;
		$rgb = utilsScs::hexToRgb( $string );
		$rgbStr = 'rgb';
		if($alpha !== false) {
			$rgb[] = $alpha;
			$rgbStr .= 'a';
		}
		return $rgbStr. '('. implode(',', $rgb). ')';
	}
	public function adjustBrightness($hex, $steps) {
		$isRgb = (strpos($hex, 'rgb') !== false);
		if($isRgb) {
			$rgbArr = utilsScs::rgbToArray($hex);
			$isRgba = count($rgbArr) == 4;
			$hex = utilsScs::rgbToHex($rgbArr);
		}
		 // Steps should be between -255 and 255. Negative = darker, positive = lighter
		$steps = max(-255, min(255, $steps));

		// Normalize into a six character long hex string
		$hex = str_replace('#', '', $hex);
		if (strlen($hex) == 3) {
			$hex = str_repeat(substr($hex, 0, 1), 2). str_repeat(substr($hex, 1, 1), 2). str_repeat(substr($hex, 2, 1), 2);
		}

		// Split into three parts: R, G and B
		$color_parts = str_split($hex, 2);
		$return = '#';

		foreach ($color_parts as $color) {
			$color   = hexdec($color); // Convert to decimal
			$color   = max(0, min(255, $color + $steps)); // Adjust color
			$return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
		}
		
		if($isRgb) {
			$return = utilsScs::hexToRgb( $return );
			if($isRgba) {	// Don't forget about alpha chanel
				$return[] = $rgbArr[ 3 ];
			}
			$return = ($isRgba ? 'rgba' : 'rgb'). '('. implode(',', $return). ')';
		}
		return $return;
	}
}
