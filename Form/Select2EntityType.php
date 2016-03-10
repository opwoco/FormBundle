<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Select2EntityType extends AbstractType
{
    private $router = null;
    private $inputClass;
    private $choiceLabel;
    
    public __construct($inputClass, $choiceLabel)
    {
        $this->inputClass = $inputClass;
        $this->choiceLabel = $choiceLabel;
    }
        
    public function setRouter($router)
    {
        $this->router = $router;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {  
        $resolver->setDefault('route', null);
        $resolver->setDefault('attr', function(Options $options, $attr){
            if($this->inputClass){
                $attr['class'] = $this->inputClass;
            }
            
            if($option['route']){
                if(!$this->router){$this->setRouter();}
                
                $attr['data-ajax--url']=$this->router->generate($options['route']);
            }
            
            return $attr;
        });
        
        $resolver->setDefault('choices', function(Options $options, $choices)){
            if($option['route']){
                $choices = array();
            };
            
            return $choices;
        }
        
        if($this->choiceLabel){
            $resolver->setDefault('choice_label',$this->choiceLabel);
        }
    }

    public function getParent()
    {
        return EntityType::class;
    }
}