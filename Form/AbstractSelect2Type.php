<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSelect2Type extends AbstractRoutableType
{
    public function configureOptions(OptionsResolver $resolver)
    {  
        if($option['route']){
            $resolver->setDefault('choices', array());
        };
    }
}
