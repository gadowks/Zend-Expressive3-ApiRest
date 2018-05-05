<?php

declare(strict_types=1);

namespace App\Handler;

use App\Model\BookModel;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;
use Zend\Expressive\Helper\UrlHelper;

class BookHandlerFactory
{
    public function __invoke(ContainerInterface $container) : BookHandler
    {
        return new BookHandler(
            $container->get(BookModel::class),
            $container->get(ResourceGenerator::class),
            $container->get(HalResponseFactory::class),
            $container->get(UrlHelper::class)
        );
    }
}
