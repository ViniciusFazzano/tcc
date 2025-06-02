
<?php

use PHPUnit\Framework\TestCase;
use App\Infra\Produto\ProdutoPdo;
use App\Dominio\Produto\Produto;
use Db\DataBase;

class ProdutoPdoTest extends TestCase
{
    private $mockDataBase;
    private $produtoPdo;

    protected function setUp(): void
    {
        $this->mockDataBase = $this->createMock(DataBase::class);
        $this->produtoPdo = new ProdutoPdo($this->mockDataBase);
    }

    public function testCadastrarProduto()
    {
        $produto = $this->createMock(Produto::class);
        $produto->method('getNome')->willReturn('Ração');
        $produto->method('getPreco')->willReturn(99.90);
        $produto->method('getEstoque')->willReturn(100);
        $produto->method('getObservacao')->willReturn('Premium');
        $produto->method('getNcm')->willReturn('12345678');

        $this->mockDataBase->expects($this->once())
            ->method('insert')->willReturn(1);

        $id = $this->produtoPdo->cadastrar($produto);
        $this->assertEquals(1, $id);
    }
}
