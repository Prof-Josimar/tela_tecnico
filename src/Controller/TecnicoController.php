<?php
namespace App\Controller;

use App\DAO\HabilidadeDAO;
use App\DAO\TecnicoDAO;
use App\Model\Tecnico;

class TecnicoController
{
    private TecnicoDAO $tecnicoDAO;
    private HabilidadeDAO $habilidadeDAO;

    public function __construct()
    {
        $this->tecnicoDAO    = new TecnicoDAO();
        $this->habilidadeDAO = new HabilidadeDAO();
    }

    public function habilidades(): array
    {
        return $this->habilidadeDAO->listarTodas();
    }

    /** Retorna lista de erros (vazia = sucesso). */
    public function salvar(array $post): array
    {
        $erros = [];

        $nome      = trim($post['nome'] ?? '');
        $endereco  = trim($post['endereco'] ?? '');
        $tel1      = trim($post['telefone1'] ?? '');
        $tel2      = trim($post['telefone2'] ?? '');
        $email     = trim($post['email'] ?? '');
        $habs      = array_map('intval', $post['habilidades'] ?? []);

        if ($nome === '')     $erros[] = 'Informe o nome.';
        if ($endereco === '') $erros[] = 'Informe o endereço.';
        if ($tel1 === '')     $erros[] = 'Informe o telefone 1.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'E-mail inválido.';
        if (!$habs)           $erros[] = 'Selecione ao menos uma habilidade.';

        if (!$erros && $this->tecnicoDAO->emailExiste($email)) {
            $erros[] = 'Este e-mail já está cadastrado.';
        }

        // só aceita ids que existem no banco
        $habs = array_map('intval', $this->habilidadeDAO->idsValidos($habs));
        if (!$erros && !$habs) $erros[] = 'Habilidades inválidas.';

        if ($erros) return $erros;

        $tecnico = new Tecnico($nome, $endereco, $tel1, $tel2 ?: null, $email, $habs);

        try {
            $this->tecnicoDAO->inserir($tecnico);
        } catch (\Throwable $e) {
            return ['Erro ao salvar o cadastro. Tente novamente.'];
        }

        return [];
    }

    public function pesquisar(string $termo): array
{
    return $this->tecnicoDAO->buscar(trim($termo));
}
}