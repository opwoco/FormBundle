<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class AutocompleteType extends AbstractType
{
    protected $router;
        
    public function __construct($router)
    {
        $this->router = $router;
    }
    
   /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {  
        $resolver->setRequired(array('route'));
		$resolver->setDefault('route_params',array());
        $resolver->setDefault('attr',function(Options $options, $attr){
            $attr['class'] = 'autocomplete-input';
            $attr['data-ajax--url']=$this->router->generate($options['route'],$options['route_params']);
            return $attr;
        });
    }

    public function getParent()
    {
        return TextType::class;
    }
}
