<?php
namespace Alsatian\FormBundle\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
class AlsatianFormExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configFormBundle = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $formTypes = array();
        
        if($configFormBundle['extensible_choice']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.extensible_choice');
            $definition->setPublic(true);
            $definition->addTag('form.type');
            
            $formTypes[] = $definition->getClass();
        }
        
        if($configFormBundle['extensible_entity']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.extensible_entity');
            $definition->setPublic(true);
            $definition->addTag('form.type');
            
            $formTypes[] = $definition->getClass();

            $container->getDefinition('alsatian_form.form_event_subscriber.extensible')
                ->addMethodCall('setEntityManager', array("@doctrine.orm.entity_manager"));
        }
        
        if($configFormBundle['extensible_document']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.extensible_document');
            $definition->setPublic(true);
            $definition->addTag('form.type');
            
            $formTypes[] = $definition->getClass();

            $container->getDefinition('alsatian_form.form_event_subscriber.extensible')
                ->addMethodCall('setDocumentManager', array("@doctrine.odm.mongodb.document_manager"));
        }
        
        if($configFormBundle['autocomplete']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.autocomplete');
            $definition->setPublic(true);
            $definition->addTag('form.type');
        }
        
        if($configFormBundle['date_picker']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.date_picker');
            $definition->setPublic(true);
            $definition->addTag('form.type');
        }
        
        if($configFormBundle['datetime_picker']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.datetime_picker');
            $definition->setPublic(true);
            $definition->addTag('form.type');
        }
        
        $container->setParameter('alsatian_form.parameters.extensible_choice.attr_class', $configFormBundle['extensible_choice']['attr_class']);
        $container->setParameter('alsatian_form.parameters.extensible_entity.attr_class', $configFormBundle['extensible_entity']['attr_class']);
        $container->setParameter('alsatian_form.parameters.extensible_document.attr_class', $configFormBundle['extensible_document']['attr_class']);
        $container->setParameter('alsatian_form.parameters.autocomplete.attr_class', $configFormBundle['autocomplete']['attr_class']);
        $container->setParameter('alsatian_form.parameters.date_picker.attr_class', $configFormBundle['date_picker']['attr_class']);
        $container->setParameter('alsatian_form.parameters.datetime_picker.attr_class', $configFormBundle['datetime_picker']['attr_class']);
        
        if($formTypes){
            $definition = $container->getDefinition('alsatian_form.form_extension.extensible');
            $definition->setPublic(true);
            $definition->addTag('form.type_extension', array('extended_type'=>'Symfony\Component\Form\Extension\Core\Type\FormType'));
            
            $container->setParameter('alsatian_form.parameters.extensible.enabled_Types', $formTypes);
        }
    }
}
