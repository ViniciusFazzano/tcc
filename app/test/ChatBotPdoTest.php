<?php

use PHPUnit\Framework\TestCase;
use App\Infra\ChatBot\ChatBotPdo;
use App\Dominio\ChatBot\ChatBot;
use Db\DataBase;

class ChatBotPdoTest extends TestCase
{
    private $mockDataBase;
    private $chatBotPdo;

    protected function setUp(): void
    {
        $this->mockDataBase = $this->createMock(DataBase::class);
        $this->chatBotPdo = new ChatBotPdo($this->mockDataBase);
    }

    public function testCadastro()
    {
        $chat = $this->createMock(ChatBot::class);
        $chat->method('getClienteId')->willReturn(1);
        $chat->method('getDataInteracao')->willReturn('2025-06-01 10:00:00');
        $chat->method('getPerguntaCliente')->willReturn('Qual produto usar?');
        $chat->method('getRespostaBot')->willReturn('Recomendo o Produto X.');
        $chat->method('getStatusResposta')->willReturn('respondido');

        $this->mockDataBase->expects($this->once())
            ->method('insert')
            ->willReturn(1);

        $id = $this->chatBotPdo->cadastro($chat);
        $this->assertEquals(1, $id);
    }
}
