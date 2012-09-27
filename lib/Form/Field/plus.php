<?php
namespace autocomplete;
class Form_Field_plus extends Form_Field_basic {
	function init(){
		parent::init();
		$f = $this->other_field;

		// Add buttonset to name field
		$bs = $f->afterField()->add('ButtonSet');
		
		// Add buttons
		$bs->add('Button')
			->set('+')
			->js('click',$f->js()->univ()->errorMessage('This should open dialog form for adding new record.'));

		$bs->add('Button') // clear current values
			->set('-')
			->js('click',array($f->js()->val(''),$this->js()->val('')));
		
		$bs->add('Button') // show current values
			->set('?')
			->js('click',$f->js()->univ()->errorMessage('Current values are:<br>ID: $this->js()->val()<br>Name: $f->js()->val()')); // How I can do this???
		
	}

}
