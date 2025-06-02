<?php

use PHPUnit\Framework\TestCase;
use App\Infra\EmpresaBatedora\EmpresaBatedoraPdo;
use App\Dominio\EmpresaBatedora\EmpresaBatedora;
use Db\DataBase;

class EmpresaBatedoraPdoTest extends TestCase
{
    private $mockDataBase;
    private $empresaBatedoraPdo;

    protected function setUp(): void
    {
        $this->mockDataBase = $this->createMock(DataBase::class);
        $this->empresaBatedoraPdo = new EmpresaBatedoraPdo($this->mockDataBase);
    }

    public function testCadastro()
    {
        $empresa = $this->createMock(EmpresaBatedora::class);
        $empresa->method('getNome')->willReturn('BatHomeo Ltda');
        $empresa->method('getCnpj')->willReturn('12345678000190');
        $empresa->method('getEndereco')->willReturn('Rua das Batidas, 100');
        $empresa->method('getTelefone')->willReturn('11999999999');

        $this->mockDataBase->expects($this->once())
            ->method('insert')
            ->willReturn(1);

        $id = $this->empresaBatedoraPdo->cadastro($empresa);
        $this->assertEquals(1, $id);
    }
}
