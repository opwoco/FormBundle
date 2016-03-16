<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Select2EntityType extends AbstractExtensibleChoicesType
{
    public function getParent()
    {
        return EntityType::class;
    }
}
