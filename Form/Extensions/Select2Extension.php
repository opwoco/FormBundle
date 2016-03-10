<?php
namespace Alsatian\FormBundle\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\FormType;

class Select2Extension extends AbstractTypeExtension
{
    private $ajaxSubscriber;

    public function __construct($ajaxSubscriber) {
        $this->ajaxSubscriber = $ajaxSubscriber;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($builder->getForm()->isRoot())
        {
            $builder->addEventSubscriber($this->ajaxSubscriber);
        }
    }
    
    public function getExtendedType()
    {
        return FormType::class;
    }
}
