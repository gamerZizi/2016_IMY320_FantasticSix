<?php
class tableOctoScs extends tableScs {
    public function __construct() {
        $this->_table = '@__octo';
        $this->_id = 'id';
        $this->_alias = 'sup_octo';
        $this->_addField('pid', 'text', 'int')
				->_addField('unique_id', 'text', 'text')
				->_addField('label', 'text', 'text')
				->_addField('active', 'text', 'int')
				->_addField('original_id', 'text', 'int')
				->_addField('is_base', 'text', 'int')
				
				->_addField('img', 'text', 'text')
				->_addField('sort_order', 'text', 'int')
				->_addField('params', 'text', 'text')
				->_addField('is_pro', 'text', 'int')
			;
    }
}