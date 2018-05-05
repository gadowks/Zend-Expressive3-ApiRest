<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Expressive\Hal\HalResponseFactory;
use Zend\Expressive\Hal\ResourceGenerator;
use Zend\Expressive\Hal\ResourceGenerator\Exception\OutOfBoundsException;
use Zend\Expressive\Helper\UrlHelper;
use DomainException;
use App\Exception;
use App\Model\BookModel;

class BookHandler implements RequestHandlerInterface
{
    private $model;
    private $resourceGenerator;
    private $responseFactory;
    private $helper;

    use RestDispatchTrait;

    public function __construct(
        BookModel $model,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory,
        UrlHelper $helper
    ) {
        $this->model = $model;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
        $this->helper = $helper;
    }

    public function get(ServerRequestInterface $request) : ResponseInterface
    {
        $id = (int) $request->getAttribute('id');

		if (!$id) {
            $page = $request->getQueryParams()['page'] ?? 1;
            $books = $this->model->getAll();
            $books->setItemCountPerPage(10);
            $books->setCurrentPageNumber($page);

            try {
                return $this->responseFactory->createResponse(
                    $request,
                    $this->resourceGenerator->fromObject($books, $request)
                );
            } catch (OutOfBoundsException $e) {
                throw Exception\OutOfBoundsException::create($e->getMessage());
            }
        }

        $book = $this->model->getBook($id);

        if (empty($book)) {
            throw Exception\NoResourceFoundException::create('Book not found');
        }

        return $this->responseFactory->createResponse(
            $request,
            $this->resourceGenerator->fromObject($book, $request)
        );
    }

    public function post(ServerRequestInterface $request) : ResponseInterface
    {
        $book = $request->getParsedBody();

        try {
            $id = $this->model->addBook($book);
        } catch (DomainException $e) {
            throw Exception\MissingParameterException::create($e->getMessage());
        }

        if ($id === null) {
            throw Exception\RuntimeException::create(
                'Ops, something went wrong. Please contact the administrator'
            );
        }

        $response = new EmptyResponse(201);

        return $response->withHeader(
            'Location',
            $this->helper->generate('api.book', ['id' => $id])
        );
    }

    public function patch(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id');

        try {
            $book = $this->model->updateBook($id, $request->getParsedBody());
        } catch (DomainException $e) {
            throw Exception\MissingParameterException::create($e->getMessage());
        }

        if (empty($book)) {
            throw Exception\NoResourceFoundException::create('Book not found');
        }

        return new JsonResponse(['book' => $book]);
    }

    public function delete(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id');
        $result = $this->model->deleteBook($id);

        if (! $result) {
            throw Exception\NoResourceFoundException::create('Book not found');
        }

        return new EmptyResponse(204);
    }
}
