<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DateTimePickerType extends AbstractType
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $attr = array_merge($options['attr'],array('class'=>'datepicker'));
        $attr['data-pattern'] = strtolower($options['date_format']);
        $builder->setAttribute('attr',$attr);
    }

   /**
    * {@inheritdoc}
    */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = $form->getConfig()->getAttribute('attr');
    }
    
   /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $intl = new \IntlDateFormatter($this->request_stack->getCurrentRequest()->getLocale(), \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
        $resolver->setDefaults(array('date_format'=>$intl->getPattern()));
    }
        
    public function getParent() {
        return DateTimeType::class;
    }

   public function getBlockPrefix()
   {
       return 'date_time';
   }
}
