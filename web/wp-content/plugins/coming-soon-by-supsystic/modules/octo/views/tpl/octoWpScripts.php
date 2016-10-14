<?php 
if($this->wpStyles)
	$this->wpStyles->do_items();
if($this->wpScripts)
	$this->wpScripts->print_scripts();
