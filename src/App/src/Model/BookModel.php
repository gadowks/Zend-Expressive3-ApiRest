<?php
namespace App\Model;

use DomainException;
use PDOException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbTableGateway;
use Zend\Paginator\Paginator;
use App\Model\Book;
use App\Model\BookCollection;

class BookModel
{
    protected $table;

    public function __construct(AdapterInterface $adapter)
    {
        $resultSet = new HydratingResultSet();
        $resultSet->setObjectPrototype(new Book());
        $this->table  = new TableGateway('books', $adapter, null, $resultSet);
    }

    public function getAll(): Paginator
    {
        $dbTableGatewayAdapter = new DbTableGateway($this->table);
        $paginator = new BookCollection($dbTableGatewayAdapter);

		return $paginator;
    }

    public function getBook(int $id): ?Book
    {
        $book = $this->table->select([ 'id' => $id ]);
        $result = $book->current();

        if ($result instanceof Book) {
            return $result;
        }

        return null;
    }

    public function addBook(array $data): ?int
    {
        if (!isset($data['title'])) {
            throw new DomainException('Title is a required field');
        }
        if (!isset($data['price'])) {
            throw new DomainException('Price is a required field');
        }
        $rows = $this->table->insert($data);

        return ($rows === 1) ? $this->table->lastInsertValue : null;
    }

    public function updateBook($id, array $data): ?Book
    {
        try {
            $rows = $this->table->update($data, [ 'id' => $id ]);
        } catch (PDOException $e) {
            throw new DomainException($e->getMessage());
        }
        return ($rows === 1) ? $this->getBook($id) : null;
    }

    public function deleteBook($id): bool
    {
        $rows = $this->table->delete([ 'id' => $id ]);
        return ($rows === 1);
    }
}
