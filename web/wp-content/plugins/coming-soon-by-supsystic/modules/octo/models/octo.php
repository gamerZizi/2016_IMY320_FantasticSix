<?php
class octoModelScs extends modelScs {
	private $_linksReplacement = array();
	public function __construct() {
		$this->_setTbl('octo');
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
	/*protected function _escTplData($data) {
		$data['html'] = dbScs::escape($data['html']);
		$data['css'] = dbScs::escape($data['css']);
		return $data;
	}*/
	public function remove($id) {
		$id = (int) $id;
		if($id) {
			if(frameScs::_()->getTable( $this->_tbl )->delete(array('id' => $id))) {
				return $this->getModule()->getModel('octo_blocks')->remove(array('oid' => $id));
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
	public function switchActive($d = array()) {
		$d['active'] = isset($d['active']) ? (int)$d['active'] : 0;
		$d['id'] = isset($d['id']) ? (int) $d['id'] : 0;
		if(!empty($d['id'])) {
			$tbl = $this->getTbl();
			return frameScs::_()->getTable($tbl)->update(array(
				'active' => $d['active'],
			), array(
				'id' => $d['id'],
			));
		} else
			$this->pushError (__('Invalid ID', SCS_LANG_CODE));
		return false;
	}
	public function isPostConverted($pid) {
		return frameScs::_()->getTable( $this->getTbl() )->exists($pid, 'pid');
	}
	public function getForPost($pid) {
		$octo = $this->setWhere(array('pid' => $pid))->getFromTbl(array('return' => 'row'));
		if($octo) {
			$octo['blocks'] = $this->getBlocksForOcto($octo['id']);
			return $octo;
		}
		return false;
	}
	public function getBlocksForOcto($oid) {
		$blocksModel = $this->getModule()->getModel('octo_blocks');
		return $blocksModel->setOrderBy('sort_order')->setSortOrder('ASC')->addWhere(array('oid' => $oid))->getFromTbl();
	}
	public function save($data = array()) {
		$oid = isset($data['id']) ? (int) $data['id'] : 0;
		if($oid) {
			if(isset($data['octo'])) {
				if(!$this->updateById($data['octo'], $oid)) {
					return false;
				}
			}
			// TODO: Add remove blocks here
			$blocksModel = $this->getModule()->getModel('octo_blocks');
			$currentBlockIds = array();
			$idSortArr = $blocksModel->getIdSortData($oid);
			if(!empty($idSortArr)) {
				foreach($idSortArr as $idSortData) {
					$currentBlockIds[ $idSortData['id'] ] = 1;
				}
			}
			if(isset($data['blocks']) && !empty($data['blocks'])) {
				foreach($data['blocks'] as $b) {
					if(!$blocksModel->save($b, $oid)) {
						$this->pushError( $blocksModel->getErrors() );
						return false;
					} else {
						if(isset($b['id']) && $b['id'] && isset($currentBlockIds[ $b['id'] ])) {
							unset( $currentBlockIds[ $b['id'] ] );
						}
					}
				}
			}
			if(!empty($currentBlockIds)) {
				$blocksModel->removeGroup(array_keys( $currentBlockIds ));
			}
			return true;
		} else
			$this->pushError (__('Invalid Octo ID', SCS_LANG_CODE));
		return false;
	}
	public function getUsedBlocksNumForPost($pid) {
		return (int) dbScs::get('SELECT COUNT(*) AS total FROM @__octo, @__octo_blocks WHERE @__octo.id = @__octo_blocks.oid AND @__octo.pid = '. (int)$pid, 'one');
	}
	public function getPresetTemplates() {
		return $this->setWhere(array('original_id' => 0, 'is_base' => 1))
			->setSelectFields('id, label, img')
			->getFromTbl();
	}
	protected function _afterGetFromTbl($row) {
		$row = parent::_afterGetFromTbl($row);
		static $imgsPath = false;
		if(!$imgsPath) {
			$imgsPath = $this->getModule()->getAssetsUrl(). 'img/tpl_prev/';
		}
		if(isset($row['img'])) {
			$row['img_preview_url'] = $imgsPath. $row['img'];
		}
		if(isset($row['params'])) {
			$row['params'] = empty($row['params']) ? array() : utilsScs::unserialize(base64_decode($row['params']), true);
			$row['params'] = $this->_afterDbReplace($this->_afterDbParams( $row['params'] ));
		}
		return $row;
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
	protected function _dataSave($data, $update = false) {
		$data = $this->_beforeDbReplace($data);
		if(isset($data['params'])) {
			$data['params'] = base64_encode(utilsScs::serialize($data['params']));
		}
		return $data;
	}
	public function copy($originalId, $data = array()) {
		$original = $this->getById($originalId);
		unset($original['id']);
		unset($original['date_created']);
		$original['is_base'] = 0;
		$original['original_id'] = $originalId;
		if(!empty($data)) {
			if(isset($data['params'])) {
				$data['params'] = array_merge($original['params'], $data['params']);
			}
			$original = array_merge($original, $data);
		}
		$oid = $this->insert( $original );
		if($oid) {
			$originalBlocks = $this->getBlocksForOcto( $originalId );
			if(!empty($originalBlocks)) {
				$blocksModel = $this->getModule()->getModel('octo_blocks');
				foreach($originalBlocks as $block) {
					$blocksModel->save(array('original_id' => $block['id']), $oid);
				}
			}
			return $oid;
		}
		return false;
	}
	public function getOctoForOriginal($originalId) {
		return $this->setWhere(array('original_id' => $originalId))->getFromTbl(array('return' => 'row'));
	}
	public function getFullById($id) {
		$octo = $this->getById( $id );
		if($octo) {
			$octo['blocks'] = $this->getBlocksForOcto($octo['id']);
			return $octo;
		}
		return false;
	}
	public function resetTpl($d = array()) {
		$oid = isset($d['id']) ? (int) $d['id'] : 0;
		if($oid) {
			return $this->remove($oid);	// Just remove it from now, after reload - it will be re-created
			//return true;
		} else
			$this->pushError (__('Invalid Octo ID', SCS_LANG_CODE));
		return false;
	}
}
