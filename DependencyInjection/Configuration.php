<?php

namespace Hessam\MailQueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bugloos_mail_queue');

        $rootNode->children()
              ->scalarNode("keep_sent_messages")
                ->defaultValue(true)
              ->end()
              ->scalarNode("keep_time")
                  ->defaultValue("2 month")
              ->end()
          ->end();

        return $treeBuilder;
    }
}
