<?php
class supsystic_promoScs extends moduleScs {
	private $_mainLink = '';
	private $_specSymbols = array(
		'from'	=> array('?', '&'),
		'to'	=> array('%', '^'),
	);
	private $_minDataInStatToSend = 20;	// At least 20 points in table shuld be present before send stats
	public function __construct($d) {
		parent::__construct($d);
		$this->getMainLink();
	}
	public function init() {
		parent::init();
		add_action('admin_footer', array($this, 'displayAdminFooter'), 9);
		if(is_admin()) {
			$this->checkStatisticStatus();
		}
		$this->weLoveYou();
		dispatcherScs::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		dispatcherScs::addAction('beforeSaveOpts', array($this, 'checkSaveOpts'));
		dispatcherScs::addAction('templateEnd', array($this, 'checkWeLoveYou'));
		dispatcherScs::addFilter('showTplsList', array($this, 'checkProTpls'));
		add_action('admin_notices', array($this, 'checkAdminPromoNotices'));
	}
	public function checkAdminPromoNotices() {
		if(!frameScs::_()->isAdminPlugOptsPage())	// Our notices - only for our plugin pages for now
			return;
		$notices = array();
		// Start usage
		$startUsage = (int) frameScs::_()->getModule('options')->get('start_usage');
		$currTime = time();
		$day = 24 * 3600;
		if($startUsage) {	// Already saved
			$rateMsg = sprintf(__("<h3>Hey, I noticed you just use %s over a week – that’s awesome!</h3><p>Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.</p>", SCS_LANG_CODE), SCS_WP_PLUGIN_NAME);
			$rateMsg .= '<p><a href="https://wordpress.org/support/view/plugin-reviews/coming-soon-by-supsystic?rate=5#postform" target="_blank" class="button button-primary" data-statistic-code="done">'. __('Ok, you deserve it', SCS_LANG_CODE). '</a>
			<a href="#" class="button" data-statistic-code="later">'. __('Nope, maybe later', SCS_LANG_CODE). '</a>
			<a href="#" class="button" data-statistic-code="hide">'. __('I already did', SCS_LANG_CODE). '</a></p>';
			$enbPromoLinkMsg = sprintf(__("<h3>More then eleven days with our %s plugin - Congratulations!</h3>", SCS_LANG_CODE), SCS_WP_PLUGIN_NAME);;
			$enbPromoLinkMsg .= __('<p>On behalf of the entire <a href="https://supsystic.com/" target="_blank">supsystic.com</a> company I would like to thank you for been with us, and I really hope that our software helped you.</p>', SCS_LANG_CODE);
			$enbPromoLinkMsg .= __('<p>And today, if you want, - you can help us. This is really simple - you can just add small promo link to our site at the bottom of your Coming Soon page. This is small step for you, but a big help for us! Sure, if you don\'t want - just skip this and continue enjoy our software!</p>', SCS_LANG_CODE);
			$enbPromoLinkMsg .= '<p><a href="#" class="button button-primary" data-statistic-code="done">'. __('Ok, you deserve it', SCS_LANG_CODE). '</a>
			<a href="#" class="button" data-statistic-code="later">'. __('Nope, maybe later', SCS_LANG_CODE). '</a>
			<a href="#" class="button" data-statistic-code="hide">'. __('Skip', SCS_LANG_CODE). '</a></p>';
			$notices = array(
				'rate_msg' => array('html' => $rateMsg, 'show_after' => 7 * $day),
				'enb_promo_link_msg' => array('html' => $enbPromoLinkMsg, 'show_after' => 11 * $day),
			);
			foreach($notices as $nKey => $n) {
				if($currTime - $startUsage <= $n['show_after']) {
					unset($notices[ $nKey ]);
					continue;
				}
				$done = (int) frameScs::_()->getModule('options')->get('done_'. $nKey);
				if($done) {
					unset($notices[ $nKey ]);
					continue;
				}
				$hide = (int) frameScs::_()->getModule('options')->get('hide_'. $nKey);
				if($hide) {
					unset($notices[ $nKey ]);
					continue;
				}
				$later = (int) frameScs::_()->getModule('options')->get('later_'. $nKey);
				if($later && ($currTime - $later) <= 2 * $day) {	// remember each 2 days
					unset($notices[ $nKey ]);
					continue;
				}
				if($nKey == 'enb_promo_link_msg' && (int)frameScs::_()->getModule('options')->get('add_love_link')) {
					unset($notices[ $nKey ]);
					continue;
				}
			}
		} else {
			frameScs::_()->getModule('options')->getModel()->save('start_usage', $currTime);
		}
		if(!empty($notices)) {
			if(isset($notices['rate_msg']) && isset($notices['enb_promo_link_msg']) && !empty($notices['enb_promo_link_msg'])) {
				unset($notices['rate_msg']);	// Show only one from those messages
			}
			$html = '';
			foreach($notices as $nKey => $n) {
				$this->getModel()->saveUsageStat($nKey. '.'. 'show', true);
				$html .= '<div class="updated notice is-dismissible supsystic-admin-notice" data-code="'. $nKey. '">'. $n['html']. '</div>';
			}
			echo $html;
		}
	}
	public function addAdminTab($tabs) {
		$tabs['overview'] = array(
			'label' => __('Overview', SCS_LANG_CODE), 'callback' => array($this, 'getOverviewTabContent'), 'fa_icon' => 'fa-info', 'sort_order' => 5, 'hidden_for_main' => true,
		);
		return $tabs;
	}
	public function getOverviewTabContent() {
		return $this->getView()->getOverviewTabContent();
	}
	// We used such methods - _encodeSlug() and _decodeSlug() - as in slug wp don't understand urlencode() functions
	private function _encodeSlug($slug) {
		return str_replace($this->_specSymbols['from'], $this->_specSymbols['to'], $slug);
	}
	private function _decodeSlug($slug) {
		return str_replace($this->_specSymbols['to'], $this->_specSymbols['from'], $slug);
	}
	public function decodeSlug($slug) {
		return $this->_decodeSlug($slug);
	}
	public function modifyMainAdminSlug($mainSlug) {
		$firstTimeLookedToPlugin = !installerScs::isUsed();
		if($firstTimeLookedToPlugin) {
			$mainSlug = $this->_getNewAdminMenuSlug($mainSlug);
		}
		return $mainSlug;
	}
	private function _getWelcomMessageMenuData($option, $modifySlug = true) {
		return array_merge($option, array(
			'page_title'	=> __('Welcome to Supsystic', SCS_LANG_CODE),
			'menu_slug'		=> ($modifySlug ? $this->_getNewAdminMenuSlug( $option['menu_slug'] ) : $option['menu_slug'] ),
			'function'		=> array($this, 'showWelcomePage'),
		));
	}
	public function addWelcomePageToMenus($options) {
		$firstTimeLookedToPlugin = !installerScs::isUsed();
		if($firstTimeLookedToPlugin) {
			foreach($options as $i => $opt) {
				$options[$i] = $this->_getWelcomMessageMenuData( $options[$i] );
			}
		}
		return $options;
	}
	private function _getNewAdminMenuSlug($menuSlug) {
		// We can't use "&" symbol in slug - so we used "|" symbol
		$newSlug = $this->_encodeSlug(str_replace('admin.php?page=', '', $menuSlug));
		return 'welcome-to-'. frameScs::_()->getModule('adminmenu')->getMainSlug(). '|return='. $newSlug;
	}
	public function addWelcomePageToMainMenu($option) {
		$firstTimeLookedToPlugin = !installerScs::isUsed();
		if($firstTimeLookedToPlugin) {
			$option = $this->_getWelcomMessageMenuData($option, false);
		}
		return $option;
	}
	public function showWelcomePage() {
		$this->getView()->showWelcomePage();
	}
	public function displayAdminFooter() {
		if(frameScs::_()->isAdminPlugPage()) {
			$this->getView()->displayAdminFooter();
		}
	}
	private function _preparePromoLink($link, $ref = '') {
		if(empty($ref))
			$ref = 'user';
		return $link;
	}
	public function weLoveYou() {
		if(!frameScs::_()->getModule(implode('', array('l','ic','e','ns','e')))) {
			// Nothing for now
		}
	}
	public function showAdditionalmainAdminShowOnOptions($popup) {
		$this->getView()->showAdditionalmainAdminShowOnOptions($popup);
	}
	/**
	 * Public shell for private method
	 */
	public function preparePromoLink($link, $ref = '') {
		return $this->_preparePromoLink($link, $ref);
	}
	public function checkStatisticStatus(){
		$canSend = (int) frameScs::_()->getModule('options')->get('send_stats');
		if($canSend) {
			$this->getModel()->checkAndSend();
		}
	}
	public function getMinStatSend() {
		return $this->_minDataInStatToSend;
	}
	public function getMainLink() {
		if(empty($this->_mainLink)) {
			$this->_mainLink = 'http://supsystic.com/plugins/coming-soon-plugin/';
		}
		return $this->_mainLink ;
	}
	public function getContactFormFields() {
		$fields = array(
            'name' => array('label' => __('Your name', SCS_LANG_CODE), 'valid' => 'notEmpty', 'html' => 'text'),
			'email' => array('label' => __('Your email', SCS_LANG_CODE), 'html' => 'email', 'valid' => array('notEmpty', 'email'), 'placeholder' => 'example@mail.com', 'def' => get_bloginfo('admin_email')),
			'website' => array('label' => __('Website', SCS_LANG_CODE), 'html' => 'text', 'placeholder' => 'http://example.com', 'def' => get_bloginfo('url')),
			'subject' => array('label' => __('Subject', SCS_LANG_CODE), 'valid' => 'notEmpty', 'html' => 'text'),
            'category' => array('label' => __('Topic', SCS_LANG_CODE), 'valid' => 'notEmpty', 'html' => 'selectbox', 'options' => array(
				'plugins_options' => __('Plugin options', SCS_LANG_CODE),
				'bug' => __('Report a bug', SCS_LANG_CODE),
				'functionality_request' => __('Require a new functionallity', SCS_LANG_CODE),
				'other' => __('Other', SCS_LANG_CODE),
			)),
			'message' => array('label' => __('Message', SCS_LANG_CODE), 'valid' => 'notEmpty', 'html' => 'textarea', 'placeholder' => __('Hello Supsystic Team!', SCS_LANG_CODE)),
        );
		foreach($fields as $k => $v) {
			if(isset($fields[ $k ]['valid']) && !is_array($fields[ $k ]['valid']))
				$fields[ $k ]['valid'] = array( $fields[ $k ]['valid'] );
		}
		return $fields;
	}
	public function isPro() {
		return frameScs::_()->getModule('license') ? true : false;
	}
	public function generateMainLink($params = '') {
		$mainLink = $this->getMainLink();
		if(!empty($params)) {
			return $mainLink. (strpos($mainLink , '?') ? '&' : '?'). $params;
		}
		return $mainLink;
	}
	public function getLoveLink() {
		$title = 'WordPress Coming Soon Plugin';
		return '<a title="'. $title. '" style="border: none; color: #26bfc1 !important; font-size: 11px; display: block; position: fixed; right: 5px; bottom: 5px;" href="'. $this->generateMainLink('utm_source=plugin&utm_medium=love_link&utm_campaign=coming_soon'). '" target="_blank">'
			. $title
			. '</a>';
	}
	public function checkSaveOpts($newValues) {
		$loveLinkEnb = (int) frameScs::_()->getModule('options')->get('add_love_link');
		$loveLinkEnbNew = isset($newValues['opt_values']['add_love_link']) ? (int) $newValues['opt_values']['add_love_link'] : 0;
		if($loveLinkEnb != $loveLinkEnbNew) {
			$this->getModel()->saveUsageStat('love_link.'. ($loveLinkEnbNew ? 'enb' : 'dslb'));
		}
		// Save mode changes
		$mode = frameScs::_()->getModule('options')->get('cs_mode');
		$modeNew = isset($newValues['opt_values']['cs_mode']) ?  $newValues['opt_values']['cs_mode'] : false;
		if($mode != $modeNew) {
			$this->getModel()->saveUsageStat('mode.'. $modeNew);
		}
		// Save template changes
		$tplId = (int) frameScs::_()->getModule('options')->get('cs_original_tpl_id');
		$tplIdNew = isset($newValues['opt_values']['cs_original_tpl_id']) ? (int) $newValues['opt_values']['cs_original_tpl_id'] : 0;
		if($tplId != $tplIdNew) {
			$this->getModel()->saveUsageStat('template.'. frameScs::_()->getModule('octo')->getModel()->getFieldById($tplIdNew, 'label'));
		}
	}
	public function checkWeLoveYou($isEditMode) {
		if(!$isEditMode && frameScs::_()->getModule('options')->get('add_love_link')) {
			echo $this->getLoveLink();
		}
	}
	public function checkProTpls($list) {
		if(!$this->isPro()) {
			$imgsPath = frameScs::_()->getModule('octo')->getAssetsUrl(). 'img/tpl_prev/';
			$promoList = array(
				array('label' => 'Flattern', 'img' => 'flattern.jpg'),
				array('label' => 'Sonoran', 'img' => 'sonoran.jpg'),
				array('label' => 'InTime', 'img' => 'intime.jpg'),
				array('label' => 'MiniGo', 'img' => 'minigo.jpg'),
				array('label' => 'Klif', 'img' => 'klif.jpg'),
				array('label' => 'Moet', 'img' => 'moet.jpg'),
				array('label' => 'Aruana', 'img' => 'aruana.jpg'),


			);
			foreach($promoList as $i => $t) {
				$promoList[ $i ]['img_preview_url'] = $imgsPath. $promoList[ $i ]['img'];
				$promoList[ $i ]['promo'] = strtolower(str_replace(array(' ', '!'), '', $t['label']));
				$promoList[ $i ]['promo_link'] = $this->generateMainLink('utm_source=plugin&utm_medium='. $promoList[ $i ]['promo']. '&utm_campaign=coming_soon');
			}
			foreach($list as $i => $t) {
				if(isset($t['is_pro']) && (int)$t['is_pro']) {
					unset($list[ $i ]);
				}
			}
			$list = array_merge($list, $promoList);
		}
		return $list;
	}
}