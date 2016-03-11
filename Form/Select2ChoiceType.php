<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Select2ChoiceType extends AbstractSelect2Type
{
    public function getParent()
    {
        return ChoiceType::class;
    }
}
