<?php
class installerScs {
	static public $update_to_version_method = '';
	static private $_firstTimeActivated = false;
	static public function init() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$current_version = get_option($wpPrefix. SCS_DB_PREF. 'db_version', 0);
		if(!$current_version)
			self::$_firstTimeActivated = true;
		/**
		 * modules 
		 */
		//dbScs::query("DROP TABLE @__modules");
		if (!dbScs::exist("@__modules")) {
			dbDelta(dbScs::prepareQuery("CREATE TABLE IF NOT EXISTS `@__modules` (
			  `id` smallint(3) NOT NULL AUTO_INCREMENT,
			  `code` varchar(32) NOT NULL,
			  `active` tinyint(1) NOT NULL DEFAULT '0',
			  `type_id` tinyint(1) NOT NULL DEFAULT '0',
			  `label` varchar(64) DEFAULT NULL,
			  `ex_plug_dir` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `code` (`code`)
			) DEFAULT CHARSET=utf8;"));
			dbScs::query("INSERT INTO `@__modules` (id, code, active, type_id, label) VALUES
				(NULL, 'adminmenu',1,1,'Admin Menu'),
				(NULL, 'options',1,1,'Options'),
				(NULL, 'user',1,1,'Users'),
				(NULL, 'pages',1,1,'Pages'),
				(NULL, 'templates',1,1,'templates'),
				(NULL, 'supsystic_promo',1,1,'supsystic_promo'),
				(NULL, 'admin_nav',1,1,'admin_nav'),
				(NULL, 'mail',1,1,'mail'),
				
				(NULL, 'octo',1,1,'octo'),
				(NULL, 'subscribe',1,1,'subscribe'),
				(NULL, 'coming_soon',1,1,'coming_soon');");
		}
		/**
		 *  modules_type 
		 */
		if(!dbScs::exist("@__modules_type")) {
			dbDelta(dbScs::prepareQuery("CREATE TABLE IF NOT EXISTS `@__modules_type` (
			  `id` smallint(3) NOT NULL AUTO_INCREMENT,
			  `label` varchar(32) NOT NULL,
			  PRIMARY KEY (`id`)
			) AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;"));
			dbScs::query("INSERT INTO `@__modules_type` VALUES
				(1,'system'),
				(6,'addons');");
		}
		/**
		 * Scso main table
		 */
		if (!dbScs::exist("@__octo")) {
			dbDelta(dbScs::prepareQuery("CREATE TABLE IF NOT EXISTS `@__octo` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`pid`  INT(11) NOT NULL DEFAULT '0',
				`unique_id` VARCHAR(8) NOT NULL,
				`label` varchar(128) NOT NULL,
				`active` TINYINT(1) NOT NULL DEFAULT '1',
				`original_id` INT(11) NOT NULL DEFAULT '0',
				`is_base` TINYINT(1) NOT NULL DEFAULT '0',
				`img` VARCHAR(64) NOT NULL,
				`sort_order` MEDIUMINT(5) NOT NULL DEFAULT '0',
				`params` TEXT NOT NULL,
				`is_pro`  TINYINT(1) NOT NULL DEFAULT '0',
				`date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8;"));
		}
		if(!dbScs::exist("@__octo", "unique_id")) {
			dbScs::query("ALTER TABLE `@__octo` ADD COLUMN `unique_id` VARCHAR(8) NOT NULL;");
		}
		if(!dbScs::exist("@__octo", "is_pro")) {
			dbScs::query("ALTER TABLE `@__octo` ADD COLUMN `is_pro` TINYINT(1) NOT NULL DEFAULT '0';");
		}
		if (!dbScs::exist("@__octo_blocks")) {
			dbDelta(dbScs::prepareQuery("CREATE TABLE IF NOT EXISTS `@__octo_blocks` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`oid`  INT(11) NOT NULL DEFAULT '0',
				`cid`  INT(11) NOT NULL DEFAULT '0',
				`unique_id` varchar(8) NOT NULL,
				`label` varchar(128) NOT NULL,
				`original_id` INT(11) NOT NULL DEFAULT '0',
				`params` TEXT NOT NULL,
				`html` TEXT NOT NULL,
				`css` TEXT NOT NULL,
				`img` varchar(64) DEFAULT NULL,
				`sort_order` MEDIUMINT(5) NOT NULL DEFAULT '0',
				`is_base` TINYINT(1) NOT NULL DEFAULT '1',
				`is_pro`  TINYINT(1) NOT NULL DEFAULT '0',
				`date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8;"));
		}
		if(!dbScs::exist("@__octo_blocks", "unique_id")) {
			dbScs::query("ALTER TABLE `@__octo_blocks` ADD COLUMN `unique_id` VARCHAR(8) NOT NULL;");
		}
		if(!dbScs::exist("@__octo_blocks", "is_pro")) {
			dbScs::query("ALTER TABLE `@__octo_blocks` ADD COLUMN `is_pro` TINYINT(1) NOT NULL DEFAULT '0';");
		}
		if (!dbScs::exist("@__octo_blocks_categories")) {
			dbDelta(dbScs::prepareQuery("CREATE TABLE IF NOT EXISTS `@__octo_blocks_categories` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`code` varchar(32) NOT NULL,
				`label` varchar(256) NOT NULL,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8;"));
		}
		self::initBaseBlocksCategories();
		self::initBaseBlocks();
		self::initBaseTemplates();
		/**
		 * Subscribers
		 */
		if (!dbScs::exist("@__subscribers")) {
			  dbDelta(dbScs::prepareQuery("CREATE TABLE `@__subscribers` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`username` VARCHAR(128) NULL DEFAULT NULL,
				`email` VARCHAR(128) NOT NULL,
				`hash` VARCHAR(128) NOT NULL,
				`activated` TINYINT(1) NOT NULL DEFAULT '0',
				`block_id` int(11) NOT NULL DEFAULT '0',
				`date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				`all_data` TEXT NOT NULL,
				PRIMARY KEY (`id`)
			  ) DEFAULT CHARSET=utf8;"));
		}
		/**
		* Plugin usage statistics
		*/
		if(!dbScs::exist("@__usage_stat")) {
			dbDelta(dbScs::prepareQuery("CREATE TABLE `@__usage_stat` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `code` varchar(64) NOT NULL,
			  `visits` int(11) NOT NULL DEFAULT '0',
			  `spent_time` int(11) NOT NULL DEFAULT '0',
			  `modify_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  UNIQUE INDEX `code` (`code`),
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8"));
			dbScs::query("INSERT INTO `@__usage_stat` (code, visits) VALUES ('installed', 1)");
		}
		installerDbUpdaterScs::runUpdate();
		if($current_version && !self::$_firstTimeActivated) {
			self::setUsed();
		}
		update_option($wpPrefix. SCS_DB_PREF. 'db_version', SCS_VERSION);
		add_option($wpPrefix. SCS_DB_PREF. 'db_installed', 1);
	}
	static public function setUsed() {
		update_option(SCS_DB_PREF. 'plug_was_used', 1);
	}
	static public function isUsed() {
		// No welcome page for now
		return true;
		return (int) get_option(SCS_DB_PREF. 'plug_was_used');
	}
	static public function delete() {
		self::_checkSendStat('delete');
		global $wpdb;
		$wpPrefix = $wpdb->prefix;
		$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.SCS_DB_PREF."modules`");
		$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.SCS_DB_PREF."modules_type`");
		$wpdb->query("DROP TABLE IF EXISTS `".$wpPrefix.SCS_DB_PREF."usage_stat`");
		delete_option($wpPrefix. SCS_DB_PREF. 'db_version');
		delete_option($wpPrefix. SCS_DB_PREF. 'db_installed');
	}
	static public function deactivate() {
		self::_checkSendStat('deactivate');
	}
	static private function _checkSendStat($statCode) {
		if(class_exists('frameScs') 
			&& frameScs::_()->getModule('supsystic_promo')
			&& frameScs::_()->getModule('options')
		) {
			frameScs::_()->getModule('supsystic_promo')->getModel()->saveUsageStat( $statCode );
			frameScs::_()->getModule('supsystic_promo')->getModel()->checkAndSend( true );
		}
	}
	static public function update() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		$currentVersion = get_option($wpPrefix. SCS_DB_PREF. 'db_version', 0);
		if(!$currentVersion || version_compare(SCS_VERSION, $currentVersion, '>')) {
			self::init();
			update_option($wpPrefix. SCS_DB_PREF. 'db_version', SCS_VERSION);
		}
	}
	static public function initBaseBlocksCategories() {
		dbScs::query('DELETE FROM @__octo_blocks_categories');
		dbScs::query('INSERT INTO @__octo_blocks_categories (id,code,label) VALUES 
			("1", "covers", "Cover"),
			("2", "sliders", "Slider"),
			("3", "galleries", "Gallery"),
			("4", "banners", "Content"),
			("5", "footers", "Footer"),
			("6", "menus", "Menu"),
			("7", "subscribes", "Subscribe Form"),
			("8", "contacts", "Contact Form"),
			("9", "grids", "Grid");');
	}
	static public function initBaseBlocks() {
		// Maybe we need to not remove other original blocks?
		// dbScs::query('DELETE FROM @__octo_blocks WHERE original_id = 0 AND is_base = 1');
		$data = array(
'HWaCYxhr' => array('oid' => '0','cid' => '4','unique_id' => 'HWaCYxhr','label' => 'MF Logo','original_id' => '0','params' => 'YTo2OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjIzOiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZyI7fXM6NToiYWxpZ24iO2E6MTp7czozOiJ2YWwiO3M6NjoiY2VudGVyIjt9czoxNDoiZmlsbF9jb2xvcl9lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMCI7fXM6MTA6ImZpbGxfY29sb3IiO2E6MTp7czozOiJ2YWwiO3M6NzoiIzAwMDAwMCI7fXM6MTg6ImZpbGxfY29sb3Jfb3BhY2l0eSI7YToxOntzOjM6InZhbCI7czozOiIwLjUiO31zOjEwOiJiZ19pbWdfZW5iIjthOjE6e3M6MzoidmFsIjtzOjE6IjAiO319','html' => '<div class=\"scsCoverOverlay\"></div>\r\n<div class=\"container scsContent\">\r\n	<div class=\"scsEl scsElImg scsElWithArea\" data-el=\"img\">\r\n      <div class=\"scsElArea\"><img src=\"[SCS_ASSETS_URL]img/blocks/mf-logo/logo.png\" /></div>\r\n  </div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsCoverOverlay {\r\n	position: absolute;\r\n  	width: 100%;\r\n  	height: 100%;\r\n  	top: 0;\r\n  	left: 0;\r\n  	background-color: {{ block.params.fill_color.val }};\r\n  	opacity: {{ block.params.fill_color_opacity.val }};\r\n    {% if not block.params.fill_color_enb.val %}\r\n    display: none;\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*main styles*/\r\n#{{block.view_id}} .scsContent {\r\n	padding-top: 25px;\r\n  	padding-bottom: 25px;\r\n}','img' => 'mf-logo.jpg','sort_order' => '1','is_base' => '1','is_pro' => '0','date_created' => '2015-09-04 16:17:23'),
'yxtx9g3O' => array('oid' => '0','cid' => '4','unique_id' => 'yxtx9g3O','label' => 'MF Header','original_id' => '0','params' => 'YTo2OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjIzOiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZyI7fXM6NToiYWxpZ24iO2E6MTp7czozOiJ2YWwiO3M6NjoiY2VudGVyIjt9czoxNDoiZmlsbF9jb2xvcl9lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMSI7fXM6MTA6ImZpbGxfY29sb3IiO2E6MTp7czozOiJ2YWwiO3M6NzoiIzAwMDAwMCI7fXM6MTg6ImZpbGxfY29sb3Jfb3BhY2l0eSI7YToxOntzOjM6InZhbCI7czoxOiIxIjt9czoxMDoiYmdfaW1nX2VuYiI7YToxOntzOjM6InZhbCI7czoxOiIwIjt9fQ==','html' => '<div class=\"scsCoverOverlay\"></div>\r\n<div class=\"container scsContent\">\r\n	<div class=\"scsEl scsMainText\" data-el=\"txt\">\r\n      <p><span style=\"color: rgb(255, 255, 255); font-size: 36pt;\" data-mce-style=\"color: #ffffff; font-size: 36pt;\">We are currently <strong>working on</strong>.</span></p>\r\n  	</div>\r\n  	<div class=\"scsEl scsElWithArea\" data-el=\"progress_bar\">\r\n      <div class=\"scsElArea scsProgrBarShell\">\r\n  		<div class=\"scsPointerProgrBar\">\r\n          	<div class=\"scsValueShellProgrBar\">\r\n        		<span class=\"scsValueProgrBar\"></span>%\r\n          	</div>\r\n        </div>\r\n      	<div class=\"scsFillProgrBar\"></div>\r\n      </div>\r\n  	</div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsCoverOverlay {\r\n	position: absolute;\r\n  	width: 100%;\r\n  	height: 100%;\r\n  	top: 0;\r\n  	left: 0;\r\n  	background-color: {{ block.params.fill_color.val }};\r\n  	opacity: {{ block.params.fill_color_opacity.val }};\r\n    {% if not block.params.fill_color_enb.val %}\r\n    display: none;\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*ignore p margin*/\r\n#{{block.view_id}} p {\r\n	margin: 0;\r\n}\r\n/*progress bar*/\r\n#{{block.view_id}} .scsProgrBarShell {\r\n	width: 100%;\r\n  	height: 6px;\r\n  	background-color: transparent;\r\n  	position: relative;\r\n}\r\n#{{block.view_id}} .scsPointerProgrBar {\r\n	position: absolute;\r\n  	top: 0;\r\n  	left: 0;\r\n}\r\n#{{block.view_id}} .scsPointerProgrBar:before {\r\n	background-color: #fff;\r\n    color: #fff;\r\n    content: \"\";\r\n    height: 18px;\r\n    position: absolute;\r\n    top: -5px;\r\n    width: 1px;\r\n}\r\n#{{block.view_id}} .scsValueShellProgrBar {\r\n	position: absolute;\r\n  	padding: 2px 5px;\r\n  	background-color: #cc361d;\r\n  	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2), 0 1px 0 #e45f48 inset;\r\n  	border: 1px solid #901b08;\r\n  	font-size: 11px;\r\n  	color: #fff;\r\n  	top: 15px;\r\n  	left: -15px;\r\n  	z-index: 9;\r\n}\r\n#{{block.view_id}} .scsFillProgrBar {\r\n	height: 100%;\r\n  	width: 0px;\r\n  	background-color: #cc361d;\r\n}\r\n/*main styles*/\r\n#{{block.view_id}} .scsMainText {\r\n	padding: 70px 0;\r\n}','img' => 'mf-header.jpg','sort_order' => '2','is_base' => '1','is_pro' => '0','date_created' => '2015-09-04 16:38:24'),
'ZursU8vF' => array('oid' => '0','cid' => '4','unique_id' => 'ZursU8vF','label' => 'MF Counter','original_id' => '0','params' => 'YTo2OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjIzOiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZyI7fXM6NToiYWxpZ24iO2E6MTp7czozOiJ2YWwiO3M6NjoiY2VudGVyIjt9czoxNDoiZmlsbF9jb2xvcl9lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMCI7fXM6MTA6ImZpbGxfY29sb3IiO2E6MTp7czozOiJ2YWwiO3M6NzoiIzAwMDAwMCI7fXM6MTg6ImZpbGxfY29sb3Jfb3BhY2l0eSI7YToxOntzOjM6InZhbCI7czozOiIwLjUiO31zOjEwOiJiZ19pbWdfZW5iIjthOjE6e3M6MzoidmFsIjtzOjE6IjAiO319','html' => '<div class=\"scsCoverOverlay\"></div>\r\n<div class=\"container scsContent\">\r\n  <div class=\"scsEl scsElWithArea\" data-el=\"timer\" data-dateformat=\"hms\" data-datemaxcol=\"sm: 9\">\r\n        <div class=\"scsElArea row\">\r\n          <div class=\"scsTimerNumShell col-sm-3\" data-num=\"hours\">\r\n              <div class=\"scsTimerNum\">0</div>\r\n              <div class=\"scsEl\" data-el=\"txt\">\r\n                  <p>HOURS</p>\r\n              </div>\r\n          </div>\r\n          <div class=\"scsTimerNumShell col-sm-3\" data-num=\"minutes\">\r\n              <div class=\"scsTimerNum\">0</div>\r\n              <div class=\"scsEl\" data-el=\"txt\">\r\n                  <p>MINUTES</p>\r\n              </div>\r\n          </div>\r\n          <div class=\"scsTimerNumShell col-sm-3\" data-num=\"seconds\">\r\n              <div class=\"scsTimerNum\">0</div>\r\n              <div class=\"scsEl\" data-el=\"txt\">\r\n                  <p>SECONDS</p>\r\n              </div>\r\n          </div>\r\n          <div class=\"scsTimerNumShell\" data-num=\"days\" style=\"display: none;\">\r\n              <div class=\"scsTimerNum\">0</div>\r\n              <div class=\"scsEl\" data-el=\"txt\">\r\n                  <p>DAYS</p>\r\n              </div>\r\n          </div>\r\n       </div>\r\n    </div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n#{{block.view_id}} .scsTimerNumShell {\r\n	float: none;\r\n  	display: inline-block;\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsCoverOverlay {\r\n	position: absolute;\r\n  	width: 100%;\r\n  	height: 100%;\r\n  	top: 0;\r\n  	left: 0;\r\n  	background-color: {{ block.params.fill_color.val }};\r\n  	opacity: {{ block.params.fill_color_opacity.val }};\r\n    {% if not block.params.fill_color_enb.val %}\r\n    display: none;\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*ignore p margin*/\r\n#{{block.view_id}} p {\r\n	margin: 0;\r\n}\r\n/*timer*/\r\n#{{block.view_id}} .scsTimerNumShell * {\r\n	font-size: 10px;\r\n}\r\n#{{block.view_id}} .scsTimerNumShell .scsTimerNum {\r\n	font-size: 90px;\r\n  	font-weight: 300;\r\n}\r\n','img' => 'mf-counter.jpg','sort_order' => '3','is_base' => '1','is_pro' => '0','date_created' => '2015-09-04 19:47:59'),
'X1jxJScA' => array('oid' => '0','cid' => '7','unique_id' => 'X1jxJScA','label' => 'MF Subscribe','original_id' => '0','params' => 'YTo5OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjQ2OiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZ3xhZGRfZmllbGR8c3ViX3NldHRpbmdzIjt9czo1OiJhbGlnbiI7YToxOntzOjM6InZhbCI7czo2OiJjZW50ZXIiO31zOjE0OiJmaWxsX2NvbG9yX2VuYiI7YToxOntzOjM6InZhbCI7czoxOiIwIjt9czoxMDoiZmlsbF9jb2xvciI7YToxOntzOjM6InZhbCI7czo3OiIjMDAwMDAwIjt9czoxODoiZmlsbF9jb2xvcl9vcGFjaXR5IjthOjE6e3M6MzoidmFsIjtzOjM6IjAuNSI7fXM6MTA6ImJnX2ltZ19lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMCI7fXM6NjoiZmllbGRzIjthOjE6e3M6MzoidmFsIjtzOjc3OiJbe1wibmFtZVwiOlwiZW1haWxcIixcImxhYmVsXCI6XCJFLW1haWxcIixcImh0bWxcIjpcImVtYWlsXCIsXCJyZXF1aXJlZFwiOjF9XSI7fXM6MTM6Im5ld19pdGVtX2h0bWwiO2E6MTp7czozOiJ2YWwiO3M6MTc5OiI8ZGl2IGNsYXNzPVwic2NzRWwgc2NzRWxJbnB1dFwiIGRhdGEtZWw9XCJpbnB1dFwiPjxkaXYgY2xhc3M9XCJzY3NJbnB1dFNoZWxsXCI+DQoJPGlucHV0IHR5cGU9XCJ0ZXh0XCIgbmFtZT1cImZfXCIgcGxhY2Vob2xkZXI9XCJOZXdfTmFtZVwiIGNsYXNzPVwic2NzRm9ybUlucHV0XCIgLz48L2Rpdj4NCjwvZGl2PiI7fXM6ODoic3ViX2Rlc3QiO2E6MTp7czozOiJ2YWwiO3M6OToid29yZHByZXNzIjt9fQ==','html' => '<div class=\"scsCoverOverlay\"></div>\r\n<div class=\"container scsContent\">\r\n	<div class=\"scsFormShell row\">\r\n    	<div class=\"col-sm-6 col-sm-offset-3\">\r\n  		{{block.sub_form_start|raw}}\r\n          <div class=\"row\">\r\n            <div class=\"scsFormFieldsShell col-sm-8\">\r\n              <div class=\"scsEl scsElInput\" data-el=\"input\"><div class=\"scsInputShell\">\r\n                <input type=\"email\" name=\"email\" placeholder=\"E-mail\" class=\"scsFormInput\" required=\"1\" /></div>\r\n              </div>\r\n            </div>\r\n            <div class=\"col-sm-4\">\r\n              <div class=\"scsEl scsElInput scsElInputBtn\" data-el=\"input_btn\"><div class=\"scsInputShell\">\r\n                <input type=\"submit\" name=\"\" value=\"SUBSCRIBE\" class=\"scsFormInput\" /></div>\r\n              </div>\r\n            </div>\r\n          </div>\r\n    	{{block.sub_form_end|raw}}\r\n		</div>\r\n	</div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsCoverOverlay {\r\n	position: absolute;\r\n  	width: 100%;\r\n  	height: 100%;\r\n  	top: 0;\r\n  	left: 0;\r\n  	background-color: {{ block.params.fill_color.val }};\r\n  	opacity: {{ block.params.fill_color_opacity.val }};\r\n    {% if not block.params.fill_color_enb.val %}\r\n    display: none;\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*ignore p margin*/\r\n#{{block.view_id}} p {\r\n	margin: 0;\r\n}\r\n/*subscribe form styles*/\r\n#{{block.view_id}} .scsFormShell {\r\n	padding: 50px 0;\r\n}\r\n#{{block.view_id}} .scsFormShell .scsElInput,\r\n#{{block.view_id}} .scsFormShell .scsInputShell {\r\n	width: 100%;\r\n}\r\n#{{block.view_id}} .scsFormShell .scsElInput {\r\n	padding-right: 15px;\r\n  	padding-bottom: 5px;\r\n}\r\n#{{block.view_id}} .scsFormInput {\r\n	width: 100%;\r\n  	padding: 5px 20px;\r\n  	height: 40px;\r\n  	color: #b0b0b0;\r\n  	border: 1px solid #a9a9a9;\r\n  	font-size: 11px;\r\n  	font-weight: 700;\r\n  	box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);\r\n  	transition: border 0.2s linear 0s, box-shadow 0.2s linear 0s;\r\n}\r\n#{{block.view_id}} .scsFormInput:focus,\r\n#{{block.view_id}} .scsFormInput:hover {\r\n	border: 1px solid #818181;\r\n}\r\n#{{block.view_id}} .scsFormInput[type=\"submit\"] {\r\n	border: 1px solid #901b08;\r\n	background-color: #cc361d;\r\n	color: #FFF;\r\n	text-shadow: 0 1px 0 #881a07;\r\n	box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2), 0 1px 0 #e45f48 inset;\r\n  	line-height: 20px;\r\n}\r\n#{{block.view_id}} .scsFormInput[type=\"submit\"]:hover {background-color: #d3381e;}\r\n#{{block.view_id}} .scsFormInput[type=\"submit\"]:focus {background-color: #bb311a;}\r\n/*main styles*/','img' => 'mf-subscribe.jpg','sort_order' => '4','is_base' => '1','is_pro' => '0','date_created' => '2015-09-04 20:25:20'),
'7jbXe3rZ' => array('oid' => '0','cid' => '5','unique_id' => '7jbXe3rZ','label' => 'MF Footer','original_id' => '0','params' => 'YTo1OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjE3OiJmaWxsX2NvbG9yfGJnX2ltZyI7fXM6MTQ6ImZpbGxfY29sb3JfZW5iIjthOjE6e3M6MzoidmFsIjtzOjE6IjAiO31zOjEwOiJmaWxsX2NvbG9yIjthOjE6e3M6MzoidmFsIjtzOjc6IiMwMDAwMDAiO31zOjE4OiJmaWxsX2NvbG9yX29wYWNpdHkiO2E6MTp7czozOiJ2YWwiO3M6MzoiMC41Ijt9czoxMDoiYmdfaW1nX2VuYiI7YToxOntzOjM6InZhbCI7czoxOiIwIjt9fQ==','html' => '<div class=\"scsCoverOverlay\"></div>\r\n<div class=\"container scsContent\">\r\n	<div class=\"row\">\r\n  		<div class=\"scsEl scsTopDelimiter col-sm-12\" data-el=\"delimiter\" data-color=\"#dbdbdb\">\r\n      		<div class=\"scsDelimContent\" style=\"background-color: #dbdbdb;\"></div>\r\n      	</div>\r\n  	</div>\r\n  	<div class=\"row\">\r\n  		<div class=\"col-sm-6 scsFooterColLeft\">\r\n      		<div class=\"scsEl\" data-el=\"txt\">\r\n      			<p><span style=\"color: rgb(169, 169, 169);\" data-mce-style=\"color: #a9a9a9;\">Copyright В© 2015 <span style=\"color: rgb(86, 86, 86);\" data-mce-style=\"color: #565656;\"><strong><a data-mce-href=\"http://supsystic.com/plugins/coming-soon-plugin/\" href=\"http://supsystic.com/plugins/coming-soon-plugin/\" target=\"_blank\" style=\"color: rgb(86, 86, 86);\" title=\"Coming Soon WordPress plugin by Supsystic\">Coming Soon WordPress plugin by Supsystic</a></strong></span></span></p>\r\n  			</div>\r\n      	</div>\r\n      	<div class=\"col-sm-6 scsFooterColRight\">\r\n      		<div class=\"scsEl\" data-el=\"txt\">\r\n          		<p><span style=\"color: rgb(86, 86, 86);\" data-mce-style=\"color: #565656;\"><strong><a data-mce-href=\"https://www.facebook.com/pages/Supsystic/1389390198028999\" target=\"_blank\" href=\"https://www.facebook.com/pages/Supsystic/1389390198028999\" style=\"color: rgb(86, 86, 86);\" data-mce-style=\"color: #565656;\">Facebook</a></strong></span> 1,695 + <span style=\"color: rgb(86, 86, 86);\" data-mce-style=\"color: #565656;\"><strong><a data-mce-href=\"https://twitter.com/supsystic\" target=\"_blank\" href=\"https://twitter.com/supsystic\" style=\"color: rgb(86, 86, 86);\" data-mce-style=\"color: #565656;\">Twitter</a></strong></span> 2,356 +</p>\r\n          	</div>\r\n      	</div>\r\n  	</div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsCoverOverlay {\r\n	position: absolute;\r\n  	width: 100%;\r\n  	height: 100%;\r\n  	top: 0;\r\n  	left: 0;\r\n  	background-color: {{ block.params.fill_color.val }};\r\n  	opacity: {{ block.params.fill_color_opacity.val }};\r\n    {% if not block.params.fill_color_enb.val %}\r\n    display: none;\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*ignore p margin*/\r\n#{{block.view_id}} p {\r\n	margin: 0;\r\n}\r\n/*main styles*/\r\n#{{block.view_id}} .scsTopDelimiter {\r\n	width: 100%;\r\n  	height: 1px;\r\n  	padding: 35px 0;\r\n}\r\n#{{block.view_id}} .scsDelimContent {\r\n  	width: 100%;\r\n	height: 1px;\r\n}\r\n#{{block.view_id}} .scsFooterColLeft {\r\n	padding-left: 0;\r\n  	text-align: left;\r\n}\r\n#{{block.view_id}} .scsFooterColLeft a {\r\n	text-decoration: blink;\r\n  	cursor: pointer;\r\n}\r\n#{{block.view_id}} .scsFooterColRight {\r\n	padding-right: 0;\r\n  	text-align: right;\r\n}\r\n@media (max-width: 768px) {\r\n	#{{block.view_id}} .scsFooterColLeft,\r\n  	#{{block.view_id}} .scsFooterColRight {\r\n  		text-align: center;\r\n  	}\r\n}','img' => 'mf-footer.jpg','sort_order' => '5','is_base' => '1','is_pro' => '0','date_created' => '2015-09-05 14:22:31'),
'jMlpEI73' => array('oid' => '0','cid' => '4','unique_id' => 'jMlpEI73','label' => 'Mercury Logo','original_id' => '0','params' => 'YTo2OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjIzOiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZyI7fXM6NToiYWxpZ24iO2E6MTp7czozOiJ2YWwiO3M6NjoiY2VudGVyIjt9czoxNDoiZmlsbF9jb2xvcl9lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMSI7fXM6MTA6ImZpbGxfY29sb3IiO2E6MTp7czozOiJ2YWwiO3M6NzoiIzAwMDAwMCI7fXM6MTg6ImZpbGxfY29sb3Jfb3BhY2l0eSI7YToxOntzOjM6InZhbCI7czoxOiIxIjt9czoxMDoiYmdfaW1nX2VuYiI7YToxOntzOjM6InZhbCI7czoxOiIwIjt9fQ==','html' => '<div class=\"scsCoverOverlay\"></div>\r\n<div class=\"container scsContent\">\r\n	<div class=\"scsEl scsElImg scsElWithArea\" data-el=\"img\">\r\n      <div class=\"scsElArea\"><img src=\"[SCS_ASSETS_URL]img/blocks/mercury-logo/logo_green.png\" /></div>\r\n  </div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsCoverOverlay {\r\n	position: absolute;\r\n  	width: 100%;\r\n  	height: 100%;\r\n  	top: 0;\r\n  	left: 0;\r\n  	background-color: {{ block.params.fill_color.val }};\r\n  	opacity: {{ block.params.fill_color_opacity.val }};\r\n    {% if not block.params.fill_color_enb.val %}\r\n    display: none;\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*main styles*/\r\n#{{block.view_id}} .scsContent {\r\n	padding-top: 25px;\r\n  	padding-bottom: 25px;\r\n}','img' => 'mercury-logo.jpg','sort_order' => '1','is_base' => '1','is_pro' => '0','date_created' => '2015-09-09 14:37:55'),
'896DK89S' => array('oid' => '0','cid' => '4','unique_id' => '896DK89S','label' => 'Mercury Header','original_id' => '0','params' => 'YTo2OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjIzOiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZyI7fXM6NToiYWxpZ24iO2E6MTp7czozOiJ2YWwiO3M6NjoiY2VudGVyIjt9czoxNDoiZmlsbF9jb2xvcl9lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMCI7fXM6MTA6ImZpbGxfY29sb3IiO2E6MTp7czozOiJ2YWwiO3M6NzoiIzAwMDAwMCI7fXM6MTg6ImZpbGxfY29sb3Jfb3BhY2l0eSI7YToxOntzOjM6InZhbCI7czozOiIwLjUiO31zOjEwOiJiZ19pbWdfZW5iIjthOjE6e3M6MzoidmFsIjtzOjE6IjAiO319','html' => '<div class=\"scsCoverOverlay\"></div>\r\n<div class=\"scsSemiTransparentLine\"></div>\r\n<div class=\"container scsContent\">\r\n	<div class=\"scsEl scsMainText\" data-el=\"txt\">\r\n      <p><strong><span style=\"font-size: 48pt;\">OUR WEBSITE </span></strong></p><p><strong><span style=\"font-size: 48pt;\">IS COMING SOON</span></strong></p><p><span data-mce-style=\"font-size: 14pt;\" style=\"font-size: 14pt;\">We\'ll be here soon with a new website. Estimated time:</span></p>\r\n  	</div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsCoverOverlay {\r\n	position: absolute;\r\n  	width: 100%;\r\n  	height: 100%;\r\n  	top: 0;\r\n  	left: 0;\r\n  	background-color: {{ block.params.fill_color.val }};\r\n  	opacity: {{ block.params.fill_color_opacity.val }};\r\n    {% if not block.params.fill_color_enb.val %}\r\n    display: none;\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*ignore p margin*/\r\n#{{block.view_id}} p {\r\n	margin: 0;\r\n}\r\n/*main styles*/\r\n#{{block.view_id}} .scsMainText {\r\n	padding: 80px 0;\r\n}\r\n#{{block.view_id}} .scsSemiTransparentLine {\r\n	width: 100%;\r\n  	height: 8px;\r\n  	background-color: rgba(0, 0, 0, 0.2);\r\n}','img' => 'mercury-header.jpg','sort_order' => '2','is_base' => '1','is_pro' => '0','date_created' => '2015-09-09 14:41:22'),
'0fvFflhG' => array('oid' => '0','cid' => '4','unique_id' => '0fvFflhG','label' => 'Mercury Counter','original_id' => '0','params' => 'YTo2OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjIzOiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZyI7fXM6NToiYWxpZ24iO2E6MTp7czozOiJ2YWwiO3M6NjoiY2VudGVyIjt9czoxNDoiZmlsbF9jb2xvcl9lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMSI7fXM6MTA6ImZpbGxfY29sb3IiO2E6MTp7czozOiJ2YWwiO3M6NzoiIzAwMDAwMCI7fXM6MTg6ImZpbGxfY29sb3Jfb3BhY2l0eSI7YToxOntzOjM6InZhbCI7czozOiIwLjIiO31zOjEwOiJiZ19pbWdfZW5iIjthOjE6e3M6MzoidmFsIjtzOjE6IjAiO319','html' => '<div class=\"container scsContent\">\r\n    <div class=\"scsCoverOverlay\"></div>\r\n  <div class=\"scsEl scsElWithArea scsMainTimer\" data-el=\"timer\" data-color=\"#fff\" style=\"color: #fff;\" data-dateformat=\"hms\" data-datemaxcol=\"sm: 9\">\r\n        <div class=\"scsElArea row\">\r\n          <div class=\"scsTimerNumShell col-sm-3\" data-num=\"hours\">\r\n              <div class=\"scsTimerNum\">0</div>\r\n              <div class=\"scsEl\" data-el=\"txt\">\r\n                  <p>HOURS</p>\r\n              </div>\r\n          </div>\r\n          <div class=\"scsTimerNumShell col-sm-3\" data-num=\"minutes\">\r\n              <div class=\"scsTimerNum\">0</div>\r\n              <div class=\"scsEl\" data-el=\"txt\">\r\n                  <p>MINUTES</p>\r\n              </div>\r\n          </div>\r\n          <div class=\"scsTimerNumShell col-sm-3\" data-num=\"seconds\">\r\n              <div class=\"scsTimerNum\">0</div>\r\n              <div class=\"scsEl\" data-el=\"txt\">\r\n                  <p>SECONDS</p>\r\n              </div>\r\n          </div>\r\n          <div class=\"scsTimerNumShell\" style=\"display: none;\" data-num=\"days\">\r\n              <div class=\"scsTimerNum\">0</div>\r\n              <div class=\"scsEl\" data-el=\"txt\">\r\n                  <p>DAYS</p>\r\n              </div>\r\n          </div>\r\n       </div>\r\n    </div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsTimerNumShell {\r\n	float: none;\r\n  	display: inline-block;\r\n}\r\n#{{block.view_id}} .scsCoverOverlay {\r\n	position: absolute;\r\n  	width: 100%;\r\n  	height: 100%;\r\n  	top: 0;\r\n  	left: 0;\r\n  	background-color: {{ block.params.fill_color.val }};\r\n  	opacity: {{ block.params.fill_color_opacity.val }};\r\n    {% if not block.params.fill_color_enb.val %}\r\n    display: none;\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*ignore p margin*/\r\n#{{block.view_id}} p {\r\n	margin: 0;\r\n}\r\n/*timer*/\r\n#{{block.view_id}} .scsTimerNumShell {\r\n	font-size: 10px;\r\n}\r\n#{{block.view_id}} .scsTimerNumShell .scsTimerNum {\r\n	font-size: 90px;\r\n  	font-weight: 800;\r\n}\r\n#{{block.view_id}} .scsMainTimer {\r\n	padding: 50px 0;\r\n}\r\n/*main*/\r\n#{{block.view_id}} .scsContent {\r\n	position: relative;\r\n}\r\n','img' => 'mercury-counter.jpg','sort_order' => '3','is_base' => '1','is_pro' => '0','date_created' => '2015-09-09 14:52:38'),
'wn8M1EBQ' => array('oid' => '0','cid' => '4','unique_id' => 'wn8M1EBQ','label' => 'Mercury Text','original_id' => '0','params' => 'YTo3OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjIzOiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZyI7fXM6NToiYWxpZ24iO2E6MTp7czozOiJ2YWwiO3M6NjoiY2VudGVyIjt9czoxNDoiZmlsbF9jb2xvcl9lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMCI7fXM6MTA6ImZpbGxfY29sb3IiO2E6MTp7czozOiJ2YWwiO3M6NzoiIzAwMDAwMCI7fXM6MTg6ImZpbGxfY29sb3Jfb3BhY2l0eSI7YToxOntzOjM6InZhbCI7czozOiIwLjIiO31zOjEwOiJiZ19pbWdfZW5iIjthOjE6e3M6MzoidmFsIjtzOjE6IjAiO31zOjE5OiJmaWxsX2NvbG9yX3NlbGVjdG9yIjthOjE6e3M6MzoidmFsIjtzOjExOiIuc2NzQ29udGVudCI7fX0=','html' => '<div class=\"container scsContent\">\r\n	<div class=\"scsEl scsMainText\" data-el=\"txt\">\r\n      <p><strong><span style=\"font-size: 18pt;\" data-mce-style=\"font-size: 18pt;\">Our website is coming soon.</span></strong></p><p><span data-mce-style=\"font-size: 12pt;\" style=\"font-size: 12pt;\">Stay tuned! Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris augue elit, dapibus dapibus porttitor id, lobortis tempor magna. Donec sapien diam, aliquet in ultricies nec. Pellentesque erat ipsum, facilisis quis ullamcorper non, ullamcorper vel velit. Morbi quis tristique nulla. <strong><a href=\"http://supsystic.com/\" target=\"_blank\" data-mce-href=\"http://supsystic.com/\">Aliquam varius</a></strong>, nulla in porttitor consectetur, dui sem feugiat ante, quis consectetur mauris lorem id quam.</span></p>\r\n  	</div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsContent {\r\n    {% if block.params.fill_color_enb.val %}\r\n    background-color: {{ hexToRgb(block.params.fill_color.val, block.params.fill_color_opacity.val) }};\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*ignore p margin*/\r\n#{{block.view_id}} p {\r\n	margin: 0;\r\n}\r\n/*links settings*/\r\n#{{block.view_id}} a,\r\n#{{block.view_id}} a:hover {\r\n	color: #000;\r\n}\r\n#{{block.view_id}} a {\r\n	text-decoration: underline;\r\n}\r\n#{{block.view_id}} a:hover {\r\n	text-decoration: none;\r\n}\r\n/*main styles*/\r\n#{{block.view_id}} .scsMainText {\r\n	padding: 0 0 50px 0;\r\n}','img' => 'mercury-text.jpg','sort_order' => '4','is_base' => '1','is_pro' => '0','date_created' => '2015-09-09 17:31:37'),
'q1TdnCkj' => array('oid' => '0','cid' => '7','unique_id' => 'q1TdnCkj','label' => 'Mercury Subscribe','original_id' => '0','params' => 'YTo5OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjQ2OiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZ3xhZGRfZmllbGR8c3ViX3NldHRpbmdzIjt9czo1OiJhbGlnbiI7YToxOntzOjM6InZhbCI7czo2OiJjZW50ZXIiO31zOjE0OiJmaWxsX2NvbG9yX2VuYiI7YToxOntzOjM6InZhbCI7czoxOiIwIjt9czoxMDoiZmlsbF9jb2xvciI7YToxOntzOjM6InZhbCI7czo3OiIjMDAwMDAwIjt9czoxODoiZmlsbF9jb2xvcl9vcGFjaXR5IjthOjE6e3M6MzoidmFsIjtzOjM6IjAuNSI7fXM6MTA6ImJnX2ltZ19lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMCI7fXM6NjoiZmllbGRzIjthOjE6e3M6MzoidmFsIjtzOjc3OiJbe1wibmFtZVwiOlwiZW1haWxcIixcImxhYmVsXCI6XCJFLW1haWxcIixcImh0bWxcIjpcImVtYWlsXCIsXCJyZXF1aXJlZFwiOjF9XSI7fXM6MTM6Im5ld19pdGVtX2h0bWwiO2E6MTp7czozOiJ2YWwiO3M6MTU2OiI8ZGl2IGRhdGEtZWw9XCJpbnB1dFwiIGNsYXNzPVwic2NzRWwgc2NzRWxJbnB1dFwiPjxkaXYgY2xhc3M9XCJzY3NJbnB1dFNoZWxsXCI+DQoJPGlucHV0IHR5cGU9XCJmX1wiIGNsYXNzPVwic2NzRm9ybUlucHV0XCIgbmFtZT1cIk5ld19OYW1lXCI+PC9kaXY+DQo8L2Rpdj4iO31zOjg6InN1Yl9kZXN0IjthOjE6e3M6MzoidmFsIjtzOjk6IndvcmRwcmVzcyI7fX0=','html' => '<div class=\"scsCoverOverlay\"></div>\r\n<div class=\"container scsContent\">\r\n  	<div class=\"row\">\r\n  		<div class=\"col-sm-6\">\r\n      		<div class=\"scsEl\" data-el=\"txt\">\r\n          		<p><strong><span style=\"font-size: 12pt;\">GET OUR NEWS</span></strong></p><p><span data-mce-style=\"font-size: 12pt;\" style=\"font-size: 12pt;\"> Enter your mail to be notified when more info is available</span></p>\r\n          	</div>\r\n          	<div class=\"scsFormShell\">\r\n				{{block.sub_form_start|raw}}\r\n				  <div class=\"row\">\r\n					<div class=\"scsFormFieldsShell col-sm-9\">\r\n					  <div class=\"scsEl scsElInput\" data-el=\"input\"><div class=\"scsInputShell\">\r\n						<input type=\"email\" name=\"email\" placeholder=\"E-mail\" class=\"scsFormInput\" required=\"1\" /></div>\r\n					  </div>\r\n					</div>\r\n					<div class=\"scsFormSubmitBtnShell col-sm-3\">\r\n					  <div class=\"scsEl scsElInput scsElInputBtn\" data-el=\"input_btn\"><div class=\"scsInputShell\">\r\n						<input type=\"submit\" name=\"\" value=\"SEND\" class=\"scsFormInput\" /></div>\r\n					  </div>\r\n					</div>\r\n				  </div>\r\n				{{block.sub_form_end|raw}}\r\n			</div>\r\n      	</div>\r\n      	<div class=\"col-sm-6\">\r\n        	<div class=\"scsEl\" data-el=\"txt\">\r\n      			<p><strong><span style=\"font-size: 12pt;\">FIND US ONLINE</span></strong></p><p><span data-mce-style=\"font-size: 12pt;\" style=\"font-size: 12pt;\"> Find us online or drop us a line</span></p>\r\n            </div>\r\n          	<div class=\"scsSocIconsShell\">\r\n	          	<div class=\"scsIcon scsEl scsElInput\" data-el=\"icon\" data-type=\"icon\" data-color=\"rgb(0, 0, 0)\" data-icon=\"fa-facebook-square\">\r\n					<a class=\"scsLink\" href=\"https://www.facebook.com/pages/Supsystic/1389390198028999\" target=\"_blank\" title=\"Supsystic\"><i class=\"fa fa-4x fa-facebook-square scsInputShell\" style=\"color: rgb(0, 0, 0)\"></i></a>\r\n				</div>\r\n              	<div class=\"scsIcon scsEl scsElInput\" data-el=\"icon\" data-type=\"icon\" data-color=\"rgb(0, 0, 0)\" data-icon=\"fa-twitter-square\">\r\n					<a class=\"scsLink\" href=\"https://twitter.com/supsystic\" target=\"_blank\" title=\"Supsystic\"><i class=\"fa fa-4x fa-twitter-square scsInputShell\" style=\"color: rgb(0, 0, 0)\"></i></a>\r\n				</div>\r\n              	<div class=\"scsIcon scsEl scsElInput\" data-el=\"icon\" data-type=\"icon\" data-color=\"rgb(0, 0, 0)\" data-icon=\"fa-pinterest-square\">\r\n					<i class=\"fa fa-4x fa-pinterest-square scsInputShell\" style=\"color: rgb(0, 0, 0)\"></i>\r\n				</div>\r\n          	</div>\r\n      	</div>\r\n  	</div>\r\n</div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n#{{block.view_id}} .scsContent {\r\n	padding: 30px 0;\r\n}\r\n/*overlay*/\r\n#{{block.view_id}} .scsCoverOverlay {\r\n	position: absolute;\r\n  	width: 100%;\r\n  	height: 100%;\r\n  	top: 0;\r\n  	left: 0;\r\n  	background-color: {{ block.params.fill_color.val }};\r\n  	opacity: {{ block.params.fill_color_opacity.val }};\r\n    {% if not block.params.fill_color_enb.val %}\r\n    display: none;\r\n    {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*ignore p margin*/\r\n#{{block.view_id}} p {\r\n	margin: 0;\r\n}\r\n/*subscribe form styles*/\r\n#{{block.view_id}} .scsFormShell {\r\n	padding-top: 10px;\r\n}\r\n#{{block.view_id}} .scsFormShell .scsElInput,\r\n#{{block.view_id}} .scsFormShell .scsInputShell {\r\n	padding: 0;\r\n  	width: 100%;\r\n}\r\n#{{block.view_id}} .scsFormFieldsShell,\r\n#{{block.view_id}} .scsFormSubmitBtnShell {\r\n	padding: 0;\r\n}\r\n#{{block.view_id}} .scsFormInput {\r\n  	width: 100%;\r\n  	height: 50px;\r\n  	line-height: 25px;\r\n  	margin: 0;\r\n  	padding: 0 20px;\r\n  	color: #444;\r\n  	border: none;\r\n  	font-size: 18px;\r\n}\r\n#{{block.view_id}} .scsFormInput[type=\"submit\"] {\r\n	color: #fff;\r\n  	background-color: #000;\r\n  	transition: all 300ms ease-in-out 0s;\r\n}\r\n#{{block.view_id}} .scsFormInput[type=\"submit\"]:hover,\r\n#{{block.view_id}} .scsFormInput[type=\"submit\"]:focus {\r\n  	opacity: 0.8;\r\n}\r\n/*social icons*/\r\n#{{block.view_id}} .scsSocIconsShell {\r\n	padding-top: 10px;\r\n}\r\n#{{block.view_id}} .scsSocIconsShell .scsIcon:not(:last-of-type) {\r\n  	margin: 0 5px 0 0;\r\n}\r\n/*main styles*/','img' => 'mercury-subscribe.jpg','sort_order' => '5','is_base' => '1','is_pro' => '0','date_created' => '2015-09-09 18:16:36'),
'owzOb8CC' => array('oid' => '0','cid' => '5','unique_id' => 'owzOb8CC','label' => 'Mercury Footer','original_id' => '0','params' => 'YTo3OntzOjEwOiJtZW51X2l0ZW1zIjthOjE6e3M6MzoidmFsIjtzOjIzOiJhbGlnbnxmaWxsX2NvbG9yfGJnX2ltZyI7fXM6NToiYWxpZ24iO2E6MTp7czozOiJ2YWwiO3M6NDoibGVmdCI7fXM6MTQ6ImZpbGxfY29sb3JfZW5iIjthOjE6e3M6MzoidmFsIjtzOjE6IjEiO31zOjEwOiJmaWxsX2NvbG9yIjthOjE6e3M6MzoidmFsIjtzOjc6IiMwMDAwMDAiO31zOjE4OiJmaWxsX2NvbG9yX29wYWNpdHkiO2E6MTp7czozOiJ2YWwiO3M6MToiMSI7fXM6MTA6ImJnX2ltZ19lbmIiO2E6MTp7czozOiJ2YWwiO3M6MToiMCI7fXM6MTk6ImZpbGxfY29sb3Jfc2VsZWN0b3IiO2E6MTp7czozOiJ2YWwiO3M6MTI6Ii5zY3NGb290TGluZSI7fX0=','html' => '<div class=\"container scsContent\">\r\n	<div class=\"scsEl scsElImg scsElWithArea scsFootImg\" data-el=\"img\">\r\n		<div class=\"scsElArea\"><img src=\"[SCS_ASSETS_URL]img/blocks/mercury-footer/supsystic-logo-small.png\" /></div>\r\n	</div>\r\n  	<div class=\"scsFootText\">\r\n  		<div class=\"scsEl\" data-el=\"txt\">\r\n          <p>Copyright В© 2015</p><p><a href=\"http://supsystic.com/plugins/coming-soon-plugin/\" title=\"Coming Soon WordPress plugin by Supsystic\">Coming Soon WordPress plugin by Supsystic</a></p>\r\n      	</div>\r\n  	</div>\r\n</div>\r\n<div class=\"scsFootLine\"></div>','css' => '/*base block setting*/\r\n#{{block.view_id}} {\r\n  {% if block.params.bg_img_enb.val %}\r\n  	background: url(\"{{ block.params.bg_img.val }}\") no-repeat center center; \r\n    -webkit-background-size: cover;\r\n    -moz-background-size: cover;\r\n    -o-background-size: cover;\r\n    background-size: cover;\r\n  	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\');\r\n-ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'{{ block.params.bg_img.val }}\', sizingMethod=\'scale\')\";\r\n  {% endif %}\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_center .scsFootText,\r\n#{{block.view_id}}.scsAlign_center .scsFootImg {\r\n	margin: 0 auto;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsFootText,\r\n#{{block.view_id}}.scsAlign_left .scsFootImg {\r\n	float: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsFootText,\r\n#{{block.view_id}}.scsAlign_right .scsFootImg {\r\n	float: right;\r\n}\r\n/*align*/\r\n#{{block.view_id}}.scsAlign_center .scsContent {\r\n	text-align: center;\r\n}\r\n#{{block.view_id}}.scsAlign_left .scsContent {\r\n	text-align: left;\r\n}\r\n#{{block.view_id}}.scsAlign_right .scsContent {\r\n	text-align: right\r\n}\r\n/*ignore p margin*/\r\n#{{block.view_id}} p {\r\n	margin: 0;\r\n}\r\n/*links style*/\r\n#{{block.view_id}} a,\r\n#{{block.view_id}} a:hover {\r\n	color: #000;\r\n  	text-decoration: blink;\r\n  	cursor: pointer;\r\n}\r\n/*main styles*/\r\n#{{block.view_id}} .scsFootLine {\r\n	width: 100%;\r\n  	height: 60px;\r\n}\r\n#{{block.view_id}} .scsFootText {\r\n	padding: 5px;\r\n}\r\n#{{block.view_id}} .scsFootText,\r\n#{{block.view_id}} .scsFootImg {\r\n	display: block;\r\n}','img' => 'mercury-footer.jpg','sort_order' => '6','is_base' => '1','is_pro' => '0','date_created' => '2015-09-10 22:37:59'),
);
		
		foreach($data as $uid => $d) {
			self::installDataByUid('@__octo_blocks', $uid, $d);
		}
	}
	static public function initBaseTemplates() {
		$data = array(
'fj3#kecf' => array('unique_id' => 'fj3#kecf','label' => 'My factory','active' => '1','original_id' => '0','is_base' => '1','img' => 'my-factory.jpg','sort_order' => '0','params' => 'YTo4OntzOjExOiJmb250X2ZhbWlseSI7czo3OiJQVCBTYW5zIjtzOjg6ImJnX2NvbG9yIjtzOjE4OiJyZ2IoMjU1LCAyNTUsIDI1NSkiO3M6NjoiYmdfaW1nIjtzOjA6IiI7czoxMDoiYmdfaW1nX3BvcyI7czo3OiJzdHJldGNoIjtzOjg6ImtleXdvcmRzIjtzOjA6IiI7czoxMToiZGVzY3JpcHRpb24iO3M6MDoiIjtzOjExOiJtYWludF9zdGFydCI7czoxMDoiMTAvMTIvMjAxNSI7czo5OiJtYWludF9lbmQiO3M6MTA6IjEwLzE0LzIwMTUiO30=','date_created' => '2015-09-03 20:14:46'),
'fjep59' => array('unique_id' => 'fjep59','label' => 'Mercury','active' => '1','original_id' => '0','is_base' => '1','img' => 'mercury.jpg','sort_order' => '1','params' => 'YTo2OntzOjExOiJmb250X2ZhbWlseSI7czo0OiJBYmVsIjtzOjExOiJtYWludF9zdGFydCI7czoxMDoiMDkvMTEvMjAxNSI7czo5OiJtYWludF9lbmQiO3M6MTA6IjA5LzEzLzIwMTUiO3M6ODoiYmdfY29sb3IiO3M6MTY6InJnYigyMywgMTYzLCA3MykiO3M6NjoiYmdfaW1nIjtzOjM3OiJbU0NTX0FTU0VUU19VUkxdaW1nL3BhdHRlcm5fZ3JlZW4uanBnIjtzOjEwOiJiZ19pbWdfcG9zIjtzOjQ6InRpbGUiO30=','date_created' => '2015-09-11 11:25:53'),
);
		$octoToBlocks = array(
'fj3#kecf' => array(
'blocks' => array('HWaCYxhr', 'yxtx9g3O', 'ZursU8vF', 'X1jxJScA', '7jbXe3rZ'),
),
'fjep59' => array(
'blocks' => array('jMlpEI73', '896DK89S', '0fvFflhG', 'wn8M1EBQ', 'q1TdnCkj', 'owzOb8CC'),
),
);
		foreach($data as $uid => $d) {
			$oid = self::installDataByUid('@__octo', $uid, $d);
			if($oid) {
				self::bindOctoToTpls($oid, $octoToBlocks[ $uid ]['blocks']);
			}
		}
	}
	static public function bindOctoToTpls($oid, $uniqueIds) {
		if(!is_numeric($oid)) {
			$oid = dbScs::get('SELECT id FROM @__octo WHERE unique_id = "'. $oid. '" AND original_id = 0 AND is_base = 1', 'one');
		}
		dbScs::query('UPDATE @__octo_blocks SET oid = '. $oid. ' WHERE unique_id IN ("'. implode('","', $uniqueIds). '") AND original_id = 0 AND is_base = 1');
	}
	static public function installDataByUid($tbl, $uid, $data) {
		$id = (int) dbScs::get("SELECT id FROM $tbl WHERE unique_id = '$uid' AND original_id = 0 AND is_base = 1", 'one');
		$action = $id ? 'UPDATE' : 'INSERT INTO';
		$values = array();
		foreach($data as $k => $v) {
			$values[] = "$k = \"$v\"";
		}
		$valuesStr = implode(',', $values);
		$query = "$action $tbl SET $valuesStr";
		if($action == 'UPDATE')
			$query .= " WHERE unique_id = '$uid' AND original_id = 0 AND is_base = 1";
		if(dbScs::query($query)) {
			return $action == 'UPDATE' ? $id : dbScs::insertID();
		}
		return false;
	}
}
