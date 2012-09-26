<?php
namespace autocomplete;
class Form_Field_plus extends Form_Field_basic {
	function init(){
		parent::init();

		$this->afterField()->add('Button')->set('+')->js('click',
			$this->js()->autocomplete('search','%')
			);
	}

}