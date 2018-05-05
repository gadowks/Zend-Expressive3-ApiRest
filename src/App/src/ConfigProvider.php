<?php

declare(strict_types=1);

namespace App;

use App\Model;
use Zend\Expressive\Hal\Metadata\MetadataMap;
use Zend\Expressive\Hal\Metadata\RouteBasedCollectionMetadata;
use Zend\Expressive\Hal\Metadata\RouteBasedResourceMetadata;
use Zend\Hydrator\ObjectProperty as ObjectPropertyHydrator;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            MetadataMap::class => $this->getMetadataMap()
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
                Handler\BookHandler::class => Handler\BookHandlerFactory::class,
                Model\BookModel::class => Model\BookModelFactory::class,
            ],
        ];
    }

    public function getMetadataMap() : array
    {
        return [
            [
                '__class__' => RouteBasedResourceMetadata::class,
                'resource_class' => Model\Book::class,
                'route' => 'api.book',
                'extractor' => ObjectPropertyHydrator::class,
            ],
            [
                '__class__' => RouteBasedCollectionMetadata::class,
                'collection_class' =>  Model\BookCollection::class,
                'collection_relation' => 'books',
                'route' => 'api.books',
            ]
        ];
    }


    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
