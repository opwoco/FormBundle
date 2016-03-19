<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractExtensibleChoicesType extends AbstractRoutableType
{
    public function configureOptions(OptionsResolver $resolver)
    {  
        parent::configureOptions($resolver);
        
        $resolver->setDefault('choices',array());
    }
}
