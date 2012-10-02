<?php
/* Addon  for converting hasOne field into auto-complete
*/
namespace autocomplete;
class Form_Field_basic extends \Form_Field_Line {
//class Form_Field_basic extends \Form_Field_Hidden { // TO DO: Should change to hidden later on and remove js->hide() line below
	
	public $options=array('mustMatch'=>'true'); // you can find all available options here: http://jqueryui.com/demos/autocomplete/
	
	protected $other_field;
	
	function init(){
		parent::init();

		// add add-on locations to pathfinder
		$l = $this->api->locate('addons',__NAMESPACE__,'location');
		$addon_location = $this->api->locate('addons',__NAMESPACE__);
		$this->api->pathfinder->addLocation($addon_location,array(
			'js'=>'js'
		))->setParent($l);

		// add additional form field
		$name = preg_replace('/_id$/','',$this->short_name);
		$caption = null;
		if($this->owner->model) {
			if($f = $this->owner->model->getField($this->short_name)) $caption = $f->caption();
		}
		$this->other_field = $this->owner->addField('line',$name,$caption);
		
		// move hidden ID field after other field. Otherwise it breaks :first->child CSS in forms
		$this->owner->add('Order')->move($this,'after',$this->other_field)->now();
		
		// $this->js(true)->closest('.atk-form-row')->hide();
	}

	function mustMatch(){
		$this->options=array_merge($this->options,array('mustMatch'=> 'true'));
		return $this;
	}

	function setNotNull($msg=null){
		$this->other_field->validateNotNull($msg);
		return $this;
	}

	function addCondition($q){
		$this->model->addCondition($this->model->title_field,'like','%'.$q.'%');
		/*
		$this->model->addCondition(
			$this->model->dsql()->orExpr()
				->where($this->model->getElement( $this->model->title_field),'like','%'.$q.'%')
				->where($this->model->getElement( $this->model->id_field),'like',
					$this->model->dsql()->getField('id','test'))
				)->debug();
		*/
	}

	function setOptions($options=array()){
		$this->options=$options;
		return $this; //maintain chain
	}

	function setModel($m){
		parent::setModel($m);

		if($_GET[$this->name]){

			if($_GET['term'])
				$this->addCondition($_GET['term']);

			$data = $this->model->getRows(array($this->model->id_field,$this->model->title_field));

			echo json_encode($data);

			exit;
		}

	}
	function render(){
		$url=$this->api->url(null,array($this->name=>'ajax'));
		if($this->value){ // on add new and insterting allow empty start value
			$this->model->tryLoad($this->value);
			$name = $this->model->get('name');
			$this->other_field->set($name);
		}
		$this->other_field->js(true)->_load('autocomplete_univ')->univ()->myautocomplete($url, $this, $this->options);

		return parent::render();
	}

}
