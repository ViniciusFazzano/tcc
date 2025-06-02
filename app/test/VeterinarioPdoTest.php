<?php

use PHPUnit\Framework\TestCase;
use App\Infra\Veterinario\VeterinarioPdo;
use App\Dominio\Veterinario\Veterinario;
use Db\DataBase;

class VeterinarioPdoTest extends TestCase
{
    private $mockDataBase;
    private $veterinarioPdo;

    protected function setUp(): void
    {
        $this->mockDataBase = $this->createMock(DataBase::class);
        $this->veterinarioPdo = new VeterinarioPdo($this->mockDataBase);
    }

    public function testCadastro()
    {
        $vet = $this->createMock(Veterinario::class);
        $vet->method('getNome')->willReturn('Dr. André');
        $vet->method('getCrmv')->willReturn('CRMV-SP12345');
        $vet->method('getEmail')->willReturn('andre@vet.com');

        $this->mockDataBase->expects($this->once())
            ->method('insert')
            ->willReturn(1);

        $id = $this->veterinarioPdo->cadastro($vet);
        $this->assertEquals(1, $id);
    }
}
