<?php
class octo_blocksModelScs extends modelScs {
	private $_linksReplacement = array();
	private $_ignoreTblBindings = false;
	//private $_tblCategories = 'octo_blocks_categories';
	public function __construct() {
		$this->_setTbl('octo_blocks');
		//$this->_setIdField('sup_octo_blocks.id');
	}
	private function _getLinksReplacement() {
		if(empty($this->_linksReplacement)) {
			$this->_linksReplacement = array(
				'modUrl' => array('url' => $this->getModule()->getModPath(), 'key' => 'SCS_MOD_URL'),
				'siteUrl' => array('url' => SCS_SITE_URL, 'key' => 'SCS_SITE_URL'),
				'assetsUrl' => array('url' => $this->getModule()->getAssetsUrl(), 'key' => 'SCS_ASSETS_URL'),
				'oldAssets' => array('url' => $this->getModule()->getOldAssetsUrl(), 'key' => 'SCS_OLD_ASSETS_URL'),
			);
		}
		return $this->_linksReplacement;
	}
	public function remove($id) {
		$deleteBy = array();
		if(is_numeric($id)) {
			$deleteBy = array('id' => (int) $id);
		} else {
			$deleteBy = $id;
		}
		if($id) {
			if(frameScs::_()->getTable( $this->_tbl )->delete( $deleteBy )) {
				return true;
			} else
				$this->pushError (__('Database error detected', SCS_LANG_CODE));
		} else
			$this->pushError(__('Invalid ID', SCS_LANG_CODE));
		return false;
	}
	/**
	 * Do not remove pre-set templates
	 */
	public function clear() {
		if(frameScs::_()->getTable( $this->_tbl )->delete(array('additionalCondition' => 'original_id != 0'))) {
			return true;
		} else 
			$this->pushError (__('Database error detected', SCS_LANG_CODE));
		return false;
	}
	protected function _buildQuery($table = null) {
		if(empty($this->_sortOrder) && empty($this->_orderBy)) {
			$this->setOrderBy('sort_order')->setSortOrder('ASC');
		}
		if($this->_ignoreTblBindings)
			return parent::_buildQuery( $table );
		$this->_selectFields = 'sup_octo_blocks.*, @__octo_blocks_categories.label AS cat_label, @__octo_blocks_categories.code AS cat_code';
		if(isset($this->_where['id'])) {
			$this->_where['additionalCondition'] = 'sup_octo_blocks.id = "'. $this->_where['id']. '"';
			unset($this->_where['id']);
		}
		parent::_buildQuery( $table );
		if(!$table)
			$table = frameScs::_()->getTable( $this->_tbl );
		$table->addJoin("INNER JOIN @__octo_blocks_categories ON @__octo_blocks_categories.id = sup_octo_blocks.cid");
	}
	private function _afterDbParams($params) {
		if(empty($params)) return $params;
		if(is_array($params)) {
			foreach($params as $k => $v) {
				$params[ $k ] = $this->_afterDbParams($v);
			}
			return $params;
		} else
			return stripslashes ($params);
	}
	protected function _afterGetFromTbl($row) {
		if($this->_ignoreTblBindings)
			return $row;
		static $imgsPath = false;
		if(!$imgsPath) {
			$imgsPath = $this->getModule()->getAssetsUrl(). 'img/blocks/';
		}
		$row['params'] = empty($row['params']) ? array() : utilsScs::unserialize(base64_decode($row['params']), true);
		$row['params'] = $this->_afterDbReplace($this->_afterDbParams( $row['params'] ));
		
		$row = $this->_afterDbReplace($row);
		$row['img_url'] = isset($row['img']) && !empty($row['img']) 
			? $imgsPath. $row['img'] 
			: $imgsPath. strtolower(str_replace(array(' ', '.'), '-', $row['label'])). '.jpg';
		$row['id'] = (int) $row['id'];
		$row['cid'] = (int) $row['cid'];
		$row['oid'] = isset($row['oid']) ? (int) $row['oid'] : 0;
		$row['original_id'] = (int) $row['original_id'];
		$row['sort_order'] = (int) $row['sort_order'];
		if(!isset($row['session_id'])) {
			$row['session_id'] = mt_rand(1, 999999);
		}
		if(!isset($row['view_id'])) {
			$row['view_id'] = 'octBlock_'. $row['session_id'];
		}
		if($row['cat_code'] == 'subscribes') {
			$row['sub_form_start'] = frameScs::_()->getModule('subscribe')->generateFormStart( $row );
			$row['sub_form_end'] = frameScs::_()->getModule('subscribe')->generateFormEnd( $row );
			
			$row['params']['fields']['val'] = isset($row['params']['fields']) && !empty($row['params']['fields']['val'])
				? utilsScs::jsonDecode($row['params']['fields']['val'])
				: array();
		}
		// Prepare param values
		if(isset($row['params']) && !empty($row['params'])) {
			// Convert int numeric values
			$intKeys = array('fill_color_enb', 'bg_img_enb');
			foreach($row['params'] as $k => $v) {
				if(in_array($k, $intKeys)) {
					$row['params'][ $k ]['val'] = (int) $row['params'][ $k ]['val'];
				}
			}
		}
		return $row;
	}
	public function getOriginalBlocks() {
		$data = $this->addWhere(array('original_id' => 0))->getFromTbl();
		return $data;
	}
	public function getOriginalBlocksByCategories() {
		$res = array();
		$catIdToIter = array();
		$blocks = $this->getOriginalBlocks();
		$i = 0;
		foreach($blocks as $b) {
			if(isset($catIdToIter[ $b['cid'] ])) {
				$res[ $catIdToIter[ $b['cid'] ] ]['blocks'][] = $b;
			} else {
				$catIdToIter[ $b['cid'] ] = $i;
				$catIcon = strtolower(str_replace(array(' ', '.', ','), '-', $b['cat_code']));
				$res[ $catIdToIter[ $b['cid'] ] ] = array(
					'id' => $b['cid'],
					'label' => $b['cat_label'],
					'icon_url' => $this->getModule()->getModPath(). 'img/categories/'. $catIcon. '.png',
					'blocks' => array(
						$b,
					),
				);
				$i++;
			}
		}
		return $res;
	}
	public function save($d = array(), $oid = 0) {
		$id = isset($d['id']) ? (int) $d['id'] : 0;
		if($id) {
			$saveData = array(
				'params' => isset($d['params']) ? $d['params'] : array(),
				'sort_order' => $d['sort_order'],
				'html' => $d['html'],
			);
			return $this->updateById( $saveData, $id );
		} else {
			// Create from original block
			$originalId = isset($d['original_id']) ? (int) $d['original_id'] : 0;
			if($originalId && ($originalBlock = $this->getById( $originalId ))) {
				$originalBlock = $this->_escTplData( $originalBlock );
				unset( $originalBlock['id'] );
				unset( $originalBlock['date_created'] );
				$originalBlock['params'] = isset($d['params']) ? $d['params'] : $originalBlock['params'];
				$originalBlock['sort_order'] = isset($d['sort_order']) ? $d['sort_order'] : $originalBlock['sort_order'];
				$originalBlock['original_id'] = $originalId;
				$originalBlock['oid'] = $oid;
				$originalBlock['html'] = isset($d['html']) ? $d['html'] : $originalBlock['html'];
				return $this->insert( $originalBlock );
			} else
				$this->pushError(__('Invalid Original ID', SCS_LANG_CODE));
		}
		return false;
	}
	public function getIdSortData($oid) {
		$this->_ignoreTblBindings = true;
		$data = $this->setSelectFields('id, sort_order')->addWhere(array('oid' => $oid))->getFromTbl();
		$this->_ignoreTblBindings = false;
		return $data;
	}
	protected function _beforeDbReplace($data) {
		static $replaceFrom, $replaceTo;
		if(is_array($data)) {
			foreach($data as $k => $v) {
				$data[ $k ] = $this->_beforeDbReplace($v);
			}
		} else {
			if(!$replaceFrom) {
				$this->_getLinksReplacement();
				foreach($this->_linksReplacement as $k => $rData) {
					if($k == 'oldAssets') {	// Replace old assets urls - to new one
						$replaceFrom[] = $rData['url'];
						$replaceTo[] = '['. $this->_linksReplacement['assetsUrl']['key']. ']';
					} else {
						$replaceFrom[] = $rData['url'];
						$replaceTo[] = '['. $rData['key']. ']';
					}
				}
			}
			$data = str_replace($replaceFrom, $replaceTo, $data);
		}
		return $data;
	}
	protected function _afterDbReplace($data) {
		static $replaceFrom, $replaceTo;
		if(is_array($data)) {
			foreach($data as $k => $v) {
				$data[ $k ] = $this->_afterDbReplace($v);
			}
		} else {
			if(!$replaceFrom) {
				$this->_getLinksReplacement();
				/*Tmp fix - for quick replace all mode URL to assets URL*/
				$replaceFrom[] = '['. $this->_linksReplacement['modUrl']['key']. ']';
				$replaceTo[] = '['. $this->_linksReplacement['assetsUrl']['key']. ']';
				$replaceFrom[] = $this->_linksReplacement['oldAssets']['url'];
				$replaceTo[] = $this->_linksReplacement['assetsUrl']['url'];
				/*****/
				foreach($this->_linksReplacement as $k => $rData) {
					$replaceFrom[] = '['. $rData['key']. ']';
					$replaceTo[] = $rData['url'];
				}
			}
			$data = str_replace($replaceFrom, $replaceTo, $data);
		}
		return $data;
	}
	protected function _dataSave($data, $update = false) {
		$data = $this->_beforeDbReplace($data);
		if(isset($data['params'])) {
			//var_dump($data['params']['fields']['val']);
			if(isset($data['params']['fields'])) {
				/*$data['params']['fields']['val'] = utilsScs::jsonEncode(isset($data['params']['fields']['val']) && !empty($data['params']['fields']['val'])
					? $data['params']['fields']['val']
					: array());*/
			}
			//var_dump($data['params']['fields']['val']);
			$data['params'] = base64_encode(utilsScs::serialize($data['params']));
		}
		return $data;
	}
	protected function _escTplData($data) {
		$data['html'] = dbScs::escape($data['html']);
		$data['css'] = dbScs::escape($data['css']);
		return $data;
	}
	public function getCategoriesList($d = array()) {
		return frameScs::_()->getTable('octo_blocks_categories')->get('*', $d);
	}
	public function generateUniqueId() {
		$uid = utilsScs::getRandStr( 8 );
		if(frameScs::_()->getTable($this->_tbl)->get('COUNT(*) AS total', array('unique_id' => $uid, 'original_id' => 0), '', 'one')) {
			return $this->generateUniqueId();
		}
		return $uid;
	}
}
