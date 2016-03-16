<?php
namespace Alsatian\FormBundle\Form;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;

class Select2DocumentType extends AbstractExtensibleChoicesType
{
    public function getParent()
    {
        return DocumentType::class;
    }
}
