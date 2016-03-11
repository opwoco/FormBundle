<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Select2EntityType extends AbstractSelect2Type
{
    public function getParent()
    {
        return EntityType::class;
    }
}
