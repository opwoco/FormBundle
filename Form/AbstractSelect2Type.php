<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSelect2Type extends AbstractType
{
    private $router = null;
    private $default_attr_class;
    
    public __construct($default_attr_class)
    {
        $this->default_attr_class = $default_attr_class;
    }
        
    public function setRouter($router)
    {
        $this->router = $router;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {  
        $resolver->setDefault('route', null);
        
        if($option['route']){
            $resolver->setDefault('choices', array());
        };
        
        if($this->default_attr_class || $option['route']){
            $resolver->setDefault('attr', function(Options $options, $attr){
                if($this->default_attr_class){
                    $attr['class'] = $this->default_attr_class;
                }
                
                if($option['route']){
                    if(!$this->router){$this->setRouter();}
                    
                    $attr['data-ajax--url']=$this->router->generate($options['route']);
                }
                
                return $attr;
            });
        }
    }
}
