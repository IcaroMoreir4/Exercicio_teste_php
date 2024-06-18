# Book Library Management System

Este projeto é um exemplo simples de um sistema de gerenciamento de biblioteca, escrito em PHP, que demonstra a criação, leitura, atualização e exclusão (CRUD) de registros de livros em um banco de dados MySQL.

## Estrutura do Projeto

O projeto consiste em duas principais classes PHP:

1. **Book**: Representa um livro na biblioteca.
2. **BookDAO**: Contém métodos para realizar operações CRUD no banco de dados para os registros de livros.

### Classe `Book`

A classe `Book` define a estrutura de um livro e contém métodos para acessar e modificar suas propriedades.

#### Propriedades

- `id`: Identificador único do livro.
- `title`: Título do livro.
- `author`: Autor do livro.
- `isbn`: Número ISBN do livro.
- `status`: Status do livro (Disponível ou Indisponível).

#### Métodos

- **__construct($title, $author, $isbn, $status = 'Disponível')**: Construtor que inicializa um novo livro.
- **getTitle()** / **setTitle($title)**: Obtém / Define o título do livro.
- **getAuthor()** / **setAuthor($author)**: Obtém / Define o autor do livro.
- **getIsbn()** / **setIsbn($isbn)**: Obtém / Define o ISBN do livro.
- **getId()** / **setId($id)**: Obtém / Define o ID do livro.
- **getStatus()** / **setStatus($status)**: Obtém / Define o status do livro.
- **fazerEmprestimo()**: Marca o livro como indisponível.
- **finalizarEmprestimo()**: Marca o livro como disponível.

### Classe `BookDAO`

A classe `BookDAO` é responsável por realizar operações CRUD no banco de dados para os registros de livros.

#### Propriedades

- `conn`: Conexão com o banco de dados.

#### Métodos

- **__construct($conn)**: Construtor que inicializa a conexão com o banco de dados.
- **create(Book $book)**: Insere um novo livro no banco de dados.
- **read($id)**: Obtém os dados de um livro pelo ID.
- **readAll()**: Obtém os dados de todos os livros.
- **update(Book $book)**: Atualiza os dados de um livro.
- **delete($id)**: Deleta um livro pelo ID.

### Função de Conexão com o Banco de Dados

A função `getConnection()` cria e retorna uma conexão PDO com o banco de dados MySQL.

```php
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
```

### Teste do DAO

O script abaixo demonstra como utilizar a classe `BookDAO` para realizar operações CRUD.

```php
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
```

## Como Usar

1. **Configurar o Banco de Dados**: Crie um banco de dados MySQL e a tabela necessária para armazenar os registros de livros.

```sql
CREATE TABLE book (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(13) NOT NULL,
    status VARCHAR(50) NOT NULL
);
```

2. **Atualizar Credenciais do Banco de Dados**: Atualize as credenciais do banco de dados na função `getConnection()`.

3. **Executar o Script de Teste**: Execute o script PHP para testar as operações CRUD.
