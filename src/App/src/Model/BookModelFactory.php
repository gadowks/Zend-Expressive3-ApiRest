<?php
namespace App\Model;

use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use App\Model\BookModel;

class BookModelFactory
{
    public function __invoke(ContainerInterface $container): BookModel
    {
        return new BookModel(
            $container->get(AdapterInterface::class)
        );
    }
}
