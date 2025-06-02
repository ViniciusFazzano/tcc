<?php

use PHPUnit\Framework\TestCase;
use App\Infra\Cliente\ClientePdo;
use App\Dominio\Cliente\Cliente;
use Db\DataBase;

class ClientePdoTest extends TestCase
{
    private $mockDataBase;
    private $clientePdo;

    protected function setUp(): void
    {
        $this->mockDataBase = $this->createMock(DataBase::class);
        $this->clientePdo = new ClientePdo($this->mockDataBase);
    }

    public function testCadastro()
    {
        $cliente = $this->createMock(Cliente::class);
        $cliente->method('getNome')->willReturn('Maria');
        $cliente->method('getCpfCnpj')->willReturn('12345678900');
        $cliente->method('getEndereco')->willReturn('Rua Exemplo');
        $cliente->method('getTelefone')->willReturn('11999999999');

        $this->mockDataBase->expects($this->once())
            ->method('setTable')->with('tb_cliente');

        $this->mockDataBase->expects($this->once())
            ->method('insert')->willReturn(1);

        $id = $this->clientePdo->cadastro($cliente);
        $this->assertEquals(1, $id);
    }
}
