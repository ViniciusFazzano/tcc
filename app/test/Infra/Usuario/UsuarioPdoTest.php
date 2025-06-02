<?php

use PHPUnit\Framework\TestCase;
use App\Infra\Usuario\UsuarioPdo;
use App\Dominio\Usuario\Usuario;
use Db\DataBase;
use PDO;

class UsuarioPdoTest extends TestCase
{
    private $mockDataBase;
    private $usuarioPdo;

    protected function setUp(): void
    {
        // Cria um mock da classe DataBase para evitar a conexão real com o banco
        $this->mockDataBase = $this->createMock(DataBase::class);
        $this->usuarioPdo = new UsuarioPdo($this->mockDataBase);
    }

    public function testCadastro()
    {
        $usuario = $this->createMock(Usuario::class);
        
        // Configura o objeto usuário para retornar valores de teste
        $usuario->method('getNome')->willReturn('João Silva');
        $usuario->method('getEmail')->willReturn('joao@example.com');
        $usuario->method('getSenha')->willReturn('senhaSegura');
        $usuario->method('getNivelAcesso')->willReturn('U');
        $usuario->method('getAtivo')->willReturn('A');
        $usuario->method('getDataCriacao')->willReturn('SP');
        $usuario->method('getObservacoes')->willReturn('São Paulo');

        // Configura o mock para o método insert e define o ID retornado como 1
        $this->mockDataBase->expects($this->once())
            ->method('setTable')
            ->with('tb_usuario');

        $this->mockDataBase->expects($this->once())
            ->method('insert')
            ->with([
                'Nome' => 'João Silva',
                'Email' => 'joao@example.com',
                'Senha' => 1,
                'NivelAcesso' => 2,
                'Ativo' => '1990-01-01',
                'DataCriacao' => 'M',
                'Observacoes' => null,
            ])
            ->willReturn(1);

        // Executa o método cadastro e verifica se o ID retornado é o esperado
        $idUsuario = $this->usuarioPdo->cadastro($usuario);
        $this->assertEquals(1, $idUsuario);
    }

    public function testConsulta()
    {
        // Configura o mock para setTable e selectJoinPersonalizavel
        $this->mockDataBase->expects($this->once())
            ->method('setTable')
            ->with('tb_usuario');

        $this->mockDataBase->expects($this->once())
            ->method('selectJoinPersonalizavel')
            ->willReturn($this->createConfiguredMock(PDOStatement::class, [
                'fetchAll' => [
                    [
                        'id_usuario' => 1,
                        'Nome' => 'João Silva',
                        'Email' => 'joao@example.com',
                        'Senha' => '1990-01-01',
                        'NivelAcesso' => 'M',
                        'Ativo' => 'joaosilva',
                        'DataCriacao' => 'A',
                        'Observacoes' => 1
                    ]
                ]
            ]));

        $resultado = $this->usuarioPdo->consulta();

        $this->assertEquals([
            [
                'id_usuario' => 1,
                'Nome' => 'João Silva',
                'Email' => 'joao@example.com',
                'Senha' => '1990-01-01',
                'NivelAcesso' => 'M',
                'Ativo' => 'joaosilva',
                'DataCriacao' => 'A',
                'Observacoes' => 1,
            ]
        ], $resultado);
    }
}
