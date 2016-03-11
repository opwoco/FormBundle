<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSelect2Type extends AbstractRoutableType
{
    public function configureOptions(OptionsResolver $resolver)
    {  
        parent::configureOptions($resolver);
        $resolver->setDefault('choices', function($options,$choices){
            if($option['route']){
                $choices = array();
            };
            
            return $choices;
        }
    }
}
