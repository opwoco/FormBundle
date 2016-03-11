<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSelect2Type extends AbstractRoutableType
{
    private $default_attr_class;
    
    public __construct($default_attr_class)
    {
        $this->default_attr_class = $default_attr_class;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {  
        if($option['route']){
            $resolver->setDefault('choices', array());
        };
        
        if($this->default_attr_class){
            $resolver->setDefault('attr', function(Options $options, $attr){
            
                $attr['class'] = $this->default_attr_class;
                                
                return $attr;
            });
        }
    }
}
