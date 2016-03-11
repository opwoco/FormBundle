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
        
        if($configFormBundle['select2_choice']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.select2_choice');
            $definition->setPublic(true);
            $definition->addTag('form.type');
            
            $formTypes[] = $definition->getClass();
        }
        
        if($configFormBundle['select2_entity']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.select2_entity');
            $definition->setPublic(true);
            $definition->addTag('form.type');
            
            $formTypes[] = $definition->getClass();
        }
        
        if($configFormBundle['select2_document']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.select2_document');
            $definition->setPublic(true);
            $definition->addTag('form.type');
            
            $formTypes[] = $definition->getClass();
        }
        
        if($configFormBundle['autocomplete']['enabled']){
            $definition = $container->getDefinition('alsatian_form.form_type.autocomplete');
            $definition->setPublic(true);
            $definition->addTag('form.type');
        }
        
        $container->setParameter('alsatian_form.parameters.select2_choice.attr_class', $configFormBundle['select2_choice']['attr_class']);
        $container->setParameter('alsatian_form.parameters.select2_entity.attr_class', $configFormBundle['select2_entity']['attr_class']);
        $container->setParameter('alsatian_form.parameters.select2_document.attr_class', $configFormBundle['select2_document']['attr_class']);
        $container->setParameter('alsatian_form.parameters.autocomplete.attr_class', $configFormBundle['autocomplete']['attr_class']);

        if($formTypes){
            $definition = $container->getDefinition('alsatian_form.form_extension.select2');
            $definition->setPublic(true);
            $definition->addTag('form.type_extension', array('extended_type'=>'Symfony\Component\Form\Extension\Core\Type\FormType'));
            
            $container->setParameter('alsatian_form.parameters.select2.enabled_Types', $formTypes);
        }
    }
}
