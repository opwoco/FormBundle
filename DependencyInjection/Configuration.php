<?php
namespace Alsatian\FormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('alsatian_form');
        
        $rootNode
            ->append($this->getNode('extensible_choice'))
            ->append($this->getNode('extensible_document'))
            ->append($this->getNode('extensible_entity'))
            ->append($this->getNode('autocomplete'))
            ->append($this->getNode('date_picker'))
            ->append($this->getNode('datetime_picker'))
            ;
        
        return $treeBuilder;
    }
    
    private function getNode($name)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);
        $node
            ->canBeEnabled()
            ->children()
                ->scalarNode('attr_class')
                    ->defaultFalse()
                ->end()
            ->end()
        ->end()
        ;
        
        return $node;
    }
}
