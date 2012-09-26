<?php
/* Addon  for converting hasOne field into auto-complete
*/
namespace autocomplete;
class Form_Field_basic extends \Form_Field_Line {
	public $options=array();
	function init(){
		parent::init();

        $l=$this->api->locate('addons',__NAMESPACE__,'location');
		$addon_location = $this->api->locate('addons',__NAMESPACE__);

        $f=preg_replace('/_id$/','',$this->short_name);

		$this->other_field = $this->owner->addField('line',$f);
		$this->js(true)->closest('.atk-form-row')->hide();


        $this->api->pathfinder->addLocation($addon_location,array(
            'js'=>'js'
        ))->setParent($l);


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

			$data = $this->model->getRows(array($model->id_field,$this->model->title_field));

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
