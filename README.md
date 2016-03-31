# FormBundle

This bundle provide FormTypes extending ChoiceType, EntityType and DocumentType to let them accept additional choices added on the client side.

Ideal for [Select2](https://select2.github.io/) integration.

Works with Symfony ~2.8 || ~3.0

Installation
============

***Download the bundle with composer***

``` bash
    composer require alsatian/form-bundle
```

***Enable the bundle***

Add the bundle to app/AppKernel.php :

``` php
// app/AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Alsatian\FormBundle\AlsatianFormBundle(),
        );

        // ...
    }

    // ...
}
```

Configuration
=============

Add following lines to app/config/config.yml

```
alsatian_form:
    extensible_choice: ~   # To enable Alsatian\FormBundle\Form\ExtensibleChoiceType
    extensible_entity: ~   # To enable Alsatian\FormBundle\Form\ExtensibleEntityType
    extensible_document: ~ # To enable Alsatian\FormBundle\Form\ExtensibleDocumentType
```    

For each of FormType you can configure a default attr_class parameter, like this :

```
alsatian_form:
    extensible_choice:
        attr_class: select2 # Adds class="select2" in the HTML input
    extensible_entity:
        attr_class: select2-entity # Adds class="select2-entity" in the HTML input
    extensible_document:
        attr_class: select2-document # Adds class="select2-document" in the HTML input
```    

Usage
=====

To use these FormTypes :

``` php
    use Alsatian\FormBundle\Form\ExtensibleChoiceType;
    use Alsatian\FormBundle\Form\ExtensibleEntityType;
    use Alsatian\FormBundle\Form\ExtensibleDocumentType;
    
    // Without route
    $builder->add('extensible_choice', ExtensibleChoiceType::class);
    $builder->add('extensible_entity', ExtensibleEntityType::class,array('class'=>'AppBundle:Article','choice_label'=>'name'));
    $builder->add('extensible_document', ExtensibleDocumentType::class,array('class'=>'AppBundle:Article','choice_label'=>'name'));

    // With route (generate the route defined as 'route' option and renders it as 'data-ajax-url' html attribute)
    $builder->add('extensible_choice', ExtensibleChoiceType::class,array('route'=>'ajax_choices'));
    $builder->add('extensible_entity', ExtensibleEntityType::class,array('route'=>'ajax_entities','class'=>'AppBundle:Article','choice_label'=>'name'));
    $builder->add('extensible_document', ExtensibleDocumentType::class,array('route'=>'ajax_documents','class'=>'AppBundle:Article','choice_label'=>'name'));
```
