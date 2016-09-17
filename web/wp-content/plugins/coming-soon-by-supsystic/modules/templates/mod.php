<?php
class templatesScs extends moduleScs {
    protected $_styles = array();
	private $_cdnUrl = '';
	
	public function __construct($d) {
		parent::__construct($d);
		$this->getCdnUrl();	// Init CDN URL
	}
	public function getCdnUrl() {
		if(empty($this->_cdnUrl)) {
			if(uriScs::isHttps()) {
				$this->_cdnUrl = 'https://supsystic.com/';
			} else {
				$this->_cdnUrl = 'http://cdn.supsystic.com/';
			}
		}
		return $this->_cdnUrl;
	}
    public function init() {
        if (is_admin()) {
			if($isAdminPlugOptsPage = frameScs::_()->isAdminPlugOptsPage()) {
				$this->loadCoreJs();
				$this->loadAdminCoreJs();
				$this->loadCoreCss();
				$this->loadChosenSelects();
				frameScs::_()->addScript('adminOptionsScs', SCS_JS_PATH. 'admin.options.js', array(), false, true);
				$this->loadTooltipstered();
				add_action('admin_enqueue_scriscs', array($this, 'loadMediaScripts'));
			}
		}
		// Some common styles - that need to be on all admin pages - be careful with them
		frameScs::_()->addStyle('supsystic-for-all-admin-'. SCS_CODE, SCS_CSS_PATH. 'supsystic-for-all-admin.css');
        
        parent::init();
    }
	public function loadTooltipstered() {
		frameScs::_()->addScript('tooltipster', $this->_cdnUrl. 'lib/tooltipster/jquery.tooltipster.min.js');
		frameScs::_()->addStyle('tooltipster', $this->_cdnUrl. 'lib/tooltipster/tooltipster.css');
		frameScs::_()->addScript('tooltipsteredScs', SCS_JS_PATH. 'tooltipstered.js', array('jquery'));
	}
	public function loadMediaScripts() {
		if(function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}
	}
	public function loadSlimscroll() {
		frameScs::_()->addScript('jquery.slimscroll', SCS_JS_PATH. 'jquery.slimscroll.js');	// Don't use CDN here - as this lib is modified
	}
	public function loadCodemirror() {
		frameScs::_()->addStyle('ptsCodemirror', $this->_cdnUrl. 'lib/codemirror/codemirror.css');
		frameScs::_()->addStyle('codemirror-addon-hint', $this->_cdnUrl. 'lib/codemirror/addon/hint/show-hint.css');
		frameScs::_()->addScript('ptsCodemirror', $this->_cdnUrl. 'lib/codemirror/codemirror.js');
		frameScs::_()->addScript('codemirror-addon-show-hint', $this->_cdnUrl. 'lib/codemirror/addon/hint/show-hint.js');
		frameScs::_()->addScript('codemirror-addon-xml-hint', $this->_cdnUrl. 'lib/codemirror/addon/hint/xml-hint.js');
		frameScs::_()->addScript('codemirror-addon-html-hint', $this->_cdnUrl. 'lib/codemirror/addon/hint/html-hint.js');
		frameScs::_()->addScript('codemirror-mode-xml', $this->_cdnUrl. 'lib/codemirror/mode/xml/xml.js');
		frameScs::_()->addScript('codemirror-mode-javascript', $this->_cdnUrl. 'lib/codemirror/mode/javascript/javascript.js');
		frameScs::_()->addScript('codemirror-mode-css', $this->_cdnUrl. 'lib/codemirror/mode/css/css.js');
		frameScs::_()->addScript('codemirror-mode-htmlmixed', $this->_cdnUrl. 'lib/codemirror/mode/htmlmixed/htmlmixed.js');
	}
	public function loadJqGrid() {
		static $loaded = false;
		if(!$loaded) {
			$this->loadJqueryUi();
			frameScs::_()->addScript('jq-grid', $this->_cdnUrl. 'lib/jqgrid/jquery.jqGrid.min.js');
			frameScs::_()->addStyle('jq-grid', $this->_cdnUrl. 'lib/jqgrid/ui.jqgrid.css');
			$langToLoad = utilsScs::getLangCode2Letter();
			$availableLocales = array('ar','bg','bg1251','cat','cn','cs','da','de','dk','el','en','es','fa','fi','fr','gl','he','hr','hr1250','hu','id','is','it','ja','kr','lt','mne','nl','no','pl','pt','pt','ro','ru','sk','sr','sr','sv','th','tr','tw','ua','vi');
			if(!in_array($langToLoad, $availableLocales)) {
				$langToLoad = 'en';
			}
			frameScs::_()->addScript('jq-grid-lang', $this->_cdnUrl. 'lib/jqgrid/i18n/grid.locale-'. $langToLoad. '.js');
			$loaded = true;
		}
	}
	public function loadFontAwesome() {
		frameScs::_()->addStyle('font-awesomeScs', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
	}
	public function loadChosenSelects() {
		frameScs::_()->addStyle('jquery.chosen', $this->_cdnUrl. 'lib/chosen/chosen.min.css');
		frameScs::_()->addScript('jquery.chosen', $this->_cdnUrl. 'lib/chosen/chosen.jquery.min.js');
	}
	public function loadJqplot() {
		static $loaded = false;
		if(!$loaded) {
			$jqplotDir = $this->_cdnUrl. 'lib/jqplot/';

			frameScs::_()->addStyle('jquery.jqplot', $jqplotDir. 'jquery.jqplot.min.css');

			frameScs::_()->addScript('jplot', $jqplotDir. 'jquery.jqplot.min.js');
			frameScs::_()->addScript('jqplot.canvasAxisLabelRenderer', $jqplotDir. 'jqplot.canvasAxisLabelRenderer.min.js');
			frameScs::_()->addScript('jqplot.canvasTextRenderer', $jqplotDir. 'jqplot.canvasTextRenderer.min.js');
			frameScs::_()->addScript('jqplot.dateAxisRenderer', $jqplotDir. 'jqplot.dateAxisRenderer.min.js');
			frameScs::_()->addScript('jqplot.canvasAxisTickRenderer', $jqplotDir. 'jqplot.canvasAxisTickRenderer.min.js');
			frameScs::_()->addScript('jqplot.highlighter', $jqplotDir. 'jqplot.highlighter.min.js');
			frameScs::_()->addScript('jqplot.cursor', $jqplotDir. 'jqplot.cursor.min.js');
			frameScs::_()->addScript('jqplot.barRenderer', $jqplotDir. 'jqplot.barRenderer.min.js');
			frameScs::_()->addScript('jqplot.categoryAxisRenderer', $jqplotDir. 'jqplot.categoryAxisRenderer.min.js');
			frameScs::_()->addScript('jqplot.pointLabels', $jqplotDir. 'jqplot.pointLabels.min.js');
			frameScs::_()->addScript('jqplot.pieRenderer', $jqplotDir. 'jqplot.pieRenderer.min.js');
			$loaded = true;
		}
	}
	public function loadMagicAnims() {
		static $loaded = false;
		if(!$loaded) {
			frameScs::_()->addStyle('jquery.jqplot', $this->_cdnUrl. 'css/magic.min.css');
			$loaded = true;
		}
	}
	public function loadAdminCoreJs() {
		frameScs::_()->addScript('jquery-ui-dialog');
		frameScs::_()->addScript('jquery-ui-slider');
		frameScs::_()->addScript('wp-color-picker');
		frameScs::_()->addScript('icheck', SCS_JS_PATH. 'icheck.min.js');
	}
	public function loadCoreJs() {
		frameScs::_()->addScript('jquery');

		frameScs::_()->addScript('commonScs', SCS_JS_PATH. 'common.js');
		frameScs::_()->addScript('coreScs', SCS_JS_PATH. 'core.js');
		
		//frameScs::_()->addScript('selecter', SCS_JS_PATH. 'jquery.fs.selecter.min.js');
		
		$ajaxurl = admin_url('admin-ajax.php');
		$jsData = array(
			'siteUrl'					=> SCS_SITE_URL,
			'imgPath'					=> SCS_IMG_PATH,
			'cssPath'					=> SCS_CSS_PATH,
			'loader'					=> SCS_LOADER_IMG, 
			'close'						=> SCS_IMG_PATH. 'cross.gif', 
			'ajaxurl'					=> $ajaxurl,
			//'options'					=> frameScs::_()->getModule('options')->getAllowedPublicOptions(),
			'SCS_CODE'					=> SCS_CODE,
			//'ball_loader'				=> SCS_IMG_PATH. 'ajax-loader-ball.gif',
			//'ok_icon'					=> SCS_IMG_PATH. 'ok-icon.png',
			'onePxImg'					=> SCS_IMG_PATH. '1px.png'
		);
		if(is_admin()) {
			$jsData['isPro'] = frameScs::_()->getModule('supsystic_promo')->isPro();
		}
		$jsData = dispatcherScs::applyFilters('jsInitVariables', $jsData);
		frameScs::_()->addJSVar('coreScs', 'SCS_DATA', $jsData);
	}
	public function loadCoreCss() {
		$this->_styles = dispatcherScs::applyFilters('coreCssList', array(
			'styleScs'			=> array('path' => SCS_CSS_PATH. 'style.css', 'for' => 'admin'), 
			'supsystic-uiScs'	=> array('path' => SCS_CSS_PATH. 'supsystic-ui.css', 'for' => 'admin'), 
			'dashicons'			=> array('for' => 'admin'),
			'bootstrap-alerts'	=> array('path' => SCS_CSS_PATH. 'bootstrap-alerts.css', 'for' => 'admin'),
			
			'icheck'			=> array('path' => SCS_CSS_PATH. 'jquery.icheck.css', 'for' => 'admin'),
			//'uniform'			=> array('path' => SCS_CSS_PATH. 'uniform.default.css', 'for' => 'admin'),
			//'selecter'			=> array('path' => SCS_CSS_PATH. 'jquery.fs.selecter.min.css', 'for' => 'admin'),
			'wp-color-picker'	=> array('for' => 'admin'),
		));
		foreach($this->_styles as $s => $sInfo) {
			if(!empty($sInfo['path'])) {
				frameScs::_()->addStyle($s, $sInfo['path']);
			} else {
				frameScs::_()->addStyle($s);
			}
		}
		$this->loadFontAwesome();
	}
	public function loadJqueryUi() {
		static $loaded = false;
		if(!$loaded) {
			frameScs::_()->addStyle('jquery-ui', SCS_CSS_PATH. 'jquery-ui.min.css');
			frameScs::_()->addStyle('jquery-ui.structure', SCS_CSS_PATH. 'jquery-ui.structure.min.css');
			frameScs::_()->addStyle('jquery-ui.theme', SCS_CSS_PATH. 'jquery-ui.theme.min.css');
			frameScs::_()->addStyle('jquery-slider', SCS_CSS_PATH. 'jquery-slider.css');
			$loaded = true;
		}
	}
	public function loadDatePicker() {
		frameScs::_()->addScript('jquery-ui-datepicker');
	}
	public function loadDateTimePicker() {
		frameScs::_()->addScript('jquery-datetimepicker', SCS_JS_PATH . 'datetimepicker/jquery.datetimepicker.min.js');
		frameScs::_()->addStyle('jquery-datetimepicker', SCS_JS_PATH . 'datetimepicker/jquery.datetimepicker.css');
	}
	public function loadBootstrap() {
		static $loaded = false;
		if(!$loaded) {
			frameScs::_()->addStyle('bootstrap', frameScs::_()->getModule('octo')->getAssetsUrl(). 'css/bootstrap.min.css');
			frameScs::_()->addStyle('bootstrap-theme', frameScs::_()->getModule('octo')->getAssetsUrl(). 'css/bootstrap-theme.min.css');
			frameScs::_()->addScript('bootstrap', SCS_JS_PATH. 'bootstrap.min.js');
			
			frameScs::_()->addStyle('jasny-bootstrap', SCS_CSS_PATH. 'jasny-bootstrap.min.css');
			frameScs::_()->addScript('jasny-bootstrap', SCS_JS_PATH. 'jasny-bootstrap.min.js');
			$loaded = true;
		}
	}
	public function loadTinyMce() {
		static $loaded = false;
		if(!$loaded) {
			frameScs::_()->addScript('scs.tinymce', SCS_JS_PATH. 'tinymce/tinymce.min.js');
			frameScs::_()->addScript('scs.jquery.tinymce', SCS_JS_PATH. 'tinymce/jquery.tinymce.min.js');
			$loaded = true;
		}
	}
	public function loadCustomColorpicker() {
		static $loaded = false;
		if(!$loaded) {
			frameScs::_()->addScript('jquery.colorpicker.spectrum', SCS_JS_PATH. 'jquery.colorpicker/spectrum.js');
			frameScs::_()->addStyle('jquery.colorpicker.spectrum', SCS_JS_PATH. 'jquery.colorpicker/spectrum.css');
			$loaded = true;
		}
	}
	public function loadCustomBootstrapColorpicker() {
		static $loaded = false;
		if(!$loaded) {
			frameScs::_()->addScript('oct.colors.script', SCS_JS_PATH. 'colorPicker/color.all.min.js');
			frameScs::_()->addStyle('oct.colors.style', SCS_JS_PATH. 'colorPicker/color.css');
			
			frameScs::_()->addScript('jquery.bootstrap.colorpicker.tinycolor', SCS_JS_PATH. 'jquery.bootstrap.colorpicker/tinycolor.js');
			frameScs::_()->addScript('jquery.bootstrap.colorpicker', SCS_JS_PATH. 'jquery.bootstrap.colorpicker/jquery.colorpickersliders.js');
			frameScs::_()->addStyle('jquery.bootstrap.colorpicker', SCS_JS_PATH. 'jquery.bootstrap.colorpicker/jquery.colorpickersliders.css');
			$loaded = true;
		}
	}
	public function loadContextMenu() {
		static $loaded = false;
		if(!$loaded) {
			frameScs::_()->addScript('jquery-ui-position');
			frameScs::_()->addScript('jquery.contextMenu', SCS_JS_PATH. 'jquery.context-menu/jquery.contextMenu.js');
			frameScs::_()->addStyle('jquery.contextMenu', SCS_JS_PATH. 'jquery.context-menu/jquery.contextMenu.css');
			$loaded = true;
		}
	}
	/**
	 * Load JS lightbox plugin, for now - this is prettyphoto
	 */
	public function loadLightbox() {
		static $loaded = false;
		if(!$loaded) {
			frameScs::_()->addScript('prettyphoto', SCS_JS_PATH. 'prettyphoto/js/jquery.prettyPhoto.js');
			frameScs::_()->addStyle('prettyphoto', SCS_JS_PATH. 'prettyphoto/css/prettyPhoto.css');
			$loaded = true;
		}
	}
}
