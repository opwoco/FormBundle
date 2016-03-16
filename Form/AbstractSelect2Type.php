<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSelect2Type extends AbstractRoutableType
{
    public function configureOptions(OptionsResolver $resolver)
    {  
        parent::configureOptions($resolver);
        
        $resolver-> replaceDefaults(array('choices'=>array()));
    }
}
