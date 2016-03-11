<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractRoutableType extends AbstractType
{
    private $router;
        
    public function setRouter($router)
    {
        $this->router = $router;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {  
        $resolver->setDefault('route', false);
                
        if($option['route']){
            $resolver->setDefault('attr', function(Options $options, $attr){

                $this->setRouter();                
                $attr['data-ajax--url']=$this->router->generate($options['route']);

                return $attr;
            });
        }
    }
}
