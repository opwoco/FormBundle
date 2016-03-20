<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;

class DatePickerType extends AbstractType
{
    protected $request_stack;
    protected $attr_class;
        
    public function __construct($request_stack,$attr_class)
    {
        $this->request_stack = $request_stack;
        $this->attr_class = $attr_class;
    }
    
   /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $intl = new \IntlDateFormatter($this->request_stack->getCurrentRequest()->getLocale(), \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
		$pattern = $intl->getPattern();

        $resolver->setDefault('format'=>$pattern,'widget'=>'single_text');

        $resolver->setDefault('attr', function(Options $options, $attr) use ($pattern){
            if($this->default_attr_class){
                $attr['class'] = $this->default_attr_class;
            }

			$attr['data-pattern'] = $pattern;
            
            return $attr;
        });
    }
        
    public function getParent() {
        return DateType::class;
    }
   public function getBlockPrefix()
   {
       return 'date';
   }
}
