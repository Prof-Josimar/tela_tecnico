<?php
namespace App\Model;

class Tecnico
{
    public function __construct(
        public string $nome = '',
        public string $endereco = '',
        public string $telefone1 = '',
        public ?string $telefone2 = null,
        public string $email = '',
        public array $habilidades = [] // ids das habilidades
    ) {}
}