<?php
namespace Alsatian\FormBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ExtensibleEntityType extends AbstractExtensibleChoicesType
{
    public function getParent()
    {
        return EntityType::class;
    }
}
