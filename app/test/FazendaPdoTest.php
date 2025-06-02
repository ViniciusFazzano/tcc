<?php

use PHPUnit\Framework\TestCase;
use App\Infra\Fazenda\FazendaPdo;
use App\Dominio\Fazenda\Fazenda;
use Db\DataBase;

class FazendaPdoTest extends TestCase
{
    private $mockDataBase;
    private $fazendaPdo;

    protected function setUp(): void
    {
        $this->mockDataBase = $this->createMock(DataBase::class);
        $this->fazendaPdo = new FazendaPdo($this->mockDataBase);
    }

    public function testCadastro()
    {
        $fazenda = $this->createMock(Fazenda::class);
        $fazenda->method('getClienteId')->willReturn(1);
        $fazenda->method('getNome')->willReturn('Fazenda Modelo');
        $fazenda->method('getLocalizacao')->willReturn('Zona Rural');
        $fazenda->method('getTamanhoHectares')->willReturn(50.5);

        $this->mockDataBase->expects($this->once())
            ->method('insert')
            ->willReturn(1);

        $id = $this->fazendaPdo->cadastro($fazenda);
        $this->assertEquals(1, $id);
    }
}
