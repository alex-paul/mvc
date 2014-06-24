<?php

/**
 * Class RegisterForm - Demo for using the form extension.
 */
class RegisterForm extends Form
{
   public function declareInputs()
   {
       $this->addInput(
           array(
               'type' => 'text',
               'name' => 'name',
               'label' => 'Nume',
               'value' => 'Ionescu',
               'extra' => array (
                     'id' => 'name',
                     'class' => 'small-imput'
               )
           )
       );

       $this->addInput(
           array(
               'type' => 'text',
               'name' => 'firstName',
               'label' => 'Prenume',
               'value' => 'Ion',
               'extra' => array (
                   'id' => 'firstName',
                   'class' => 'small-imput'
               )
           )
       );

       $this->addInput(
           array(
               'type' => 'select',
               'name' => 'sex',
               'value' => 'masculin',
               'label' => 'Sex',
               'options' => array (
                   'feminin',
                   'masculin',
                   'nespecificat'
               ),
               'extra' => array (
                   'id' => 'sex',
                   'class' => 'small-imput'
               )
           )
       );

       $this->addInput(
           array(
               'type' => 'submit',
               'name' => 'submit',
               'value' => 'Inregistrare',
           )
       );
   }
}