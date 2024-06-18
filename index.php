<?php

// require_once 'Conexao.php'; // Inclua a conexão do banco de dados aqui

// 1 - Criar Classes.

// Classe Book representa um livro na biblioteca
class Book {
    private $id;
    private $title;
    private $author;
    private $isbn;
    private $status;

    // Construtor da classe Book
    public function __construct($title, $author, $isbn, $status = 'Disponível') {
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->status = $status;
    }

    // Métodos getters e setters
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function getIsbn() {
        return $this->isbn;
    }

    public function setIsbn($isbn) {
        $this->isbn = $isbn;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    // Método para realizar empréstimo do livro
    public function fazerEmprestimo() {
        if ($this->status == 'Disponível') {
            $this->status = 'Indisponível';
            return true;
        } else {
            echo 'O livro está indisponível';
            return false;
        }
    }

    // Método para finalizar empréstimo do livro
    public function finalizarEmprestimo() {
        if ($this->status == 'Indisponível') {
            $this->status = 'Disponível';
            return true;
        } else {
            echo 'O livro já está disponível';
            return false;
        }
    }
}

// 2 - Criar DAOs.

// Classe BookDAO para operações no banco de dados
class BookDAO {
    protected $conn;

    // Construtor da classe BookDAO
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Método para criar um livro no banco de dados
    public function create(Book $book) {
        $sql = 'INSERT INTO book (title, author, isbn, status) VALUES (:title, :author, :isbn, :status)';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['title' => $book->getTitle(), 'author' => $book->getAuthor(), 'isbn' => $book->getIsbn(), 'status' => $book->getStatus()]);
    }

    // Método para ler um livro do banco de dados pelo ID
    public function read($id) {
        $sql = 'SELECT * FROM book WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para ler todos os livros do banco de dados
    public function readAll() {
        $sql = 'SELECT * FROM book';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para atualizar um livro no banco de dados
    public function update(Book $book) {
        $sql = 'UPDATE book SET title = :title, author = :author, isbn = :isbn, status = :status WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['title' => $book->getTitle(), 'author' => $book->getAuthor(), 'isbn' => $book->getIsbn(), 'status' => $book->getStatus(), 'id' => $book->getId()]);
    }

    // Método para deletar um livro do banco de dados pelo ID
    public function delete($id) {
        $sql = 'DELETE FROM book WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}

// 3 - Testar o DAO.

// Função de conexão com o banco de dados
function getConnection() {
    $dsn = 'mysql:host=localhost;dbname=your_database';
    $username = 'your_username';
    $password = 'your_password';

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }   
}

// Criação de uma instância de BookDAO
$conn = getConnection();
$bookDAO = new BookDAO($conn);

// Teste do método create
$newBook = new Book('Test Title', 'Test Author', '1234567890');
$bookDAO->create($newBook);
echo "Book created successfully.\n";

// Teste do método read
$bookId = 1; // Substitua pelo ID do livro que você criou
$book = $bookDAO->read($bookId);
print_r($book);

// Teste do método readAll
$books = $bookDAO->readAll();
print_r($books);

// Teste do método update
$updatedBook = new Book('Updated Title', 'Updated Author', '0987654321', 'Disponível');
$updatedBook->setId($bookId); // Defina o ID do livro que deseja atualizar
$bookDAO->update($updatedBook);
echo "Book updated successfully.\n";

// Teste do método delete
$bookDAO->delete($bookId);
echo "Book deleted successfully.\n";

?>
