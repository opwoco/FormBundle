<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractRoutableType extends AbstractType
{
    protected $router;
    protected $default_attr_class;
    
    public function __construct($default_attr_class)
    {
        $this->default_attr_class = $default_attr_class;
    }
        
    public function setRouter($router)
    {
        $this->router = $router;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {  
        $resolver->setDefaults(array('route'=>false,'route_params'=>array()));        

        $resolver->setDefault('attr', function(Options $options, $attr){
            if($this->default_attr_class){
                $attr['class'] = $this->default_attr_class;
            }
            
            if($option['route']){
                $this->setRouter();                
                $attr['data-ajax--url']=$this->router->generate($options['route'],$options['route_params']);
            }
            
            return $attr;
        });
    }
}
