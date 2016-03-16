<?php
namespace Alsatian\FormBundle\Form;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;

class ExtensibleDocumentType extends AbstractExtensibleChoicesType
{
    public function getParent()
    {
        return DocumentType::class;
    }
}
