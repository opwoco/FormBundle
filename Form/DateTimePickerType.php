<?php
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $intl = new \IntlDateFormatter($this->request_stack->getCurrentRequest()->getLocale(), \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
		$pattern = $intl->getPattern();

        $resolver->setDefaults(array('date_format'=>$pattern,'widget'=>'single_text'));

        $resolver->setDefault('attr', function(Options $options, $attr) use ($pattern){
            if($this->default_attr_class){
                $attr['class'] = $this->default_attr_class;
            }

			$attr['data-pattern'] = $pattern;
            
            return $attr;
        });
    }
        
    public function getParent() {
        return DateTimeType::class;
    }
   public function getBlockPrefix()
   {
       return 'date_time';
   }
}
