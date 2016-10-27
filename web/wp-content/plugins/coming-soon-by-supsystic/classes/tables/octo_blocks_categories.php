<?php
class tableOcto_blocks_categoriesScs extends tableScs {
    public function __construct() {
        $this->_table = '@__octo_blocks_categories';
        $this->_id = 'id';
        $this->_alias = 'sup_octo_blocks_categories';
		
        $this->_addField('code', 'text', 'text')
			->_addField('label', 'text', 'text');
    }
}