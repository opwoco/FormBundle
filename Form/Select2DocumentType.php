<?php
namespace Alsatian\FormBundle\Form;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;

class Select2DocumentType extends AbstractSelect2Type
{
    public function getParent()
    {
        return DocumentType::class;
    }
}
