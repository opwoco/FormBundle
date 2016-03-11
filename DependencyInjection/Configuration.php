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
            ->append($this->getSelect2Node('select2_choice'))
            ->append($this->getSelect2Node('select2_document'))
            ->append($this->getSelect2Node('select2_entity'))
            ->append($this->getSelect2Node('autocomplete'))
            ;
        
        return $treeBuilder;
    }
    
    private function getSelect2Node($name)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);
        $node
            ->canBeEnabled()
            ->children()
                ->arrayNode('defaults')
                    ->children()
                        ->scalarNode('attr_class')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;
        
        return $node;
    }
}
