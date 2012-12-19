<?php
namespace autocomplete;
class Form_Field_plus extends Form_Field_basic {
	function init(){
		parent::init();
		$f = $this->other_field;

		// Add buttonset to name field
		$bs = $f->afterField()->add('ButtonSet');
		

        $self=$this;
		// Add buttons
		$bs->add('Button') // open dialog for adding new element
			->set('+')
            ->add('misc/PageInFrame')->bindEvent('click','Add New Record')->set(function($page)use($self){
                $form=$page->add('Form');
                $form->setModel($self->model);
                if($form->isSubmitted()){
                    $form->update();
                    $js=array();
                    $js[]=$self->js()->val($form->model->id);
                    $js[]=$self->other_field->js()->val($form->model[$form->model->title_field]);
                    $form->js(null,$js)->univ()->closeDialog()->execute();
                }
            });

		
	}

}
