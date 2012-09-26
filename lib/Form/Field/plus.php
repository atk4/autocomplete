<?php
namespace autocomplete;
class Form_Field_plus extends Form_Field_basic {
	function init(){
		parent::init();

		$v=$this->afterField()->add('ButtonSet');
		//$v->add('Text')->set('123');
		$v->add('Button')->set('+');
		$v->add('Button')->set('-');
		//$v->addStyle('width','300px');
		/*->add('Button')->set('+')->js('click',
			$this->js()->autocomplete('search','%')
			);
			*/
	}

}