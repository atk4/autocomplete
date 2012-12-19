<?php
/**
 * Addon  for converting hasOne field into auto-complete
 */
namespace autocomplete;
class Form_Field_basic extends \Form_Field_Hidden {
	
	public $options=array('mustMatch'=>true); // you can find all available options here: http://jqueryui.com/demos/autocomplete/
	
	public $limit_rows = 20; // Limits resultset
	public $min_length = 3; // Minimum characters you have to enter to make autocomplete ajax call
	public $hint = 'Please enter at least %s symbols. Search results will be limited to %s records.'; // Hint text. If empty/null, then hint will not be shown.
	
	public $other_field;
	
	function init(){
		parent::init();

		// add add-on locations to pathfinder
		$l = $this->api->locate('addons',__NAMESPACE__,'location');
		$addon_location = $this->api->locate('addons',__NAMESPACE__);
		$this->api->pathfinder->addLocation($addon_location,array(
			'js'=>'js',
			'css'=>'templates/css',
		))->setParent($l);
		
		// add additional form field
		$name = preg_replace('/_id$/','',$this->short_name);
		$caption = null;
		if($this->owner->model) {
			if($f = $this->owner->model->getField($this->short_name)) $caption = $f->caption();
		}
		$this->other_field = $this->owner->addField('line',$name,$caption);
		if($this->hint) $this->other_field->setFieldHint(sprintf($this->hint,$this->min_length,$this->limit_rows));
		
		// move hidden ID field after other field. Otherwise it breaks :first->child CSS in forms
		$this->js(true)->appendTo($this->other_field->js()->parent());
		
		// Set default options
		if($this->min_length) $this->options['minLength'] = $this->min_length;
	}

	function mustMatch(){
		$this->options=array_merge($this->options,array('mustMatch'=> 'true'));
		return $this;
	}

	function validateNotNull($msg=null){
		$this->other_field->validateNotNull($msg);
		return $this;
	}

	function addCondition($q){
		$this->model->addCondition($this->model->title_field,'like','%'.$q.'%'); // add condition
		/*
		$this->model->addCondition(
			$this->model->dsql()->orExpr()
				->where($this->model->getElement( $this->model->title_field),'like','%'.$q.'%')
				->where($this->model->getElement( $this->model->id_field),'like',$this->model->dsql()->getField('id','test'))
		)->debug();
		*/
		$this->model->setOrder($this->model->title_field); // order ascending by title field
		if($this->limit_rows) $this->model->_dsql()->limit($this->limit_rows); // limit resultset

		return $this;
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
		if($this->value){ // on add new and inserting allow empty start value
			$this->model->tryLoad($this->value);
			$name = $this->model->get($this->model->title_field);
			$this->other_field->set($name);
		}
		
		$this->other_field->js(true)
			->_load('autocomplete_univ')
			->_css('autocomplete')
			->univ()->myautocomplete($url, $this, $this->options, $this->model->id_field, $this->model->title_field);

		return parent::render();
	}
	
}
