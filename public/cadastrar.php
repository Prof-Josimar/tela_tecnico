<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controller\TecnicoController;

$controller = new TecnicoController();
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erros = $controller->salvar($_POST);

    if (!$erros) {
        header('Location: cadastrar.php?ok=1'); // <- mudou
        exit;
    }
}

$habilidades = $controller->habilidades();
$old = $_POST ?? [];
$marcadas = array_map('intval', $old['habilidades'] ?? []);

function e(?string $v): string {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Técnico Freelancer</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f3f4f6; margin:0; padding:24px; }
        .card { max-width:560px; margin:auto; background:#fff; padding:24px; border-radius:8px; }
        label { display:block; margin-top:14px; font-weight:bold; }
        input[type=text], input[type=email], input[type=tel] {
            width:100%; padding:8px; margin-top:4px; box-sizing:border-box;
        }
        .skills label { font-weight:normal; margin-top:6px; }
        button { margin-top:20px; padding:10px 20px; cursor:pointer; }
        .erro { background:#fee2e2; color:#991b1b; padding:10px; border-radius:4px; margin-bottom:10px; }
        .ok   { background:#dcfce7; color:#166534; padding:10px; border-radius:4px; margin-bottom:10px; }
    </style>
</head>
<body>
<div class="card">
    <p><a href="index.php">&larr; Voltar ao menu</a></p> <!-- novo -->
    <h2>Cadastro de Técnico de TI (Freelance)</h2>

    <?php if (isset($_GET['ok'])): ?>
        <div class="ok">Cadastro realizado com sucesso!</div>
    <?php endif; ?>

    <?php foreach ($erros as $erro): ?>
        <div class="erro"><?= e($erro) ?></div>
    <?php endforeach; ?>

    <form method="post" action="cadastrar.php"> <!-- mudou -->
        <label>Nome
            <input type="text" name="nome" value="<?= e($old['nome'] ?? '') ?>" required>
        </label>

        <label>Endereço
            <input type="text" name="endereco" value="<?= e($old['endereco'] ?? '') ?>" required>
        </label>

        <label>Telefone 1
            <input type="tel" name="telefone1" value="<?= e($old['telefone1'] ?? '') ?>" required>
        </label>

        <label>Telefone 2
            <input type="tel" name="telefone2" value="<?= e($old['telefone2'] ?? '') ?>">
        </label>

        <label>E-mail
            <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required>
        </label>

        <label>Habilidades</label>
        <div class="skills">
            <?php foreach ($habilidades as $h): ?>
                <label>
                    <input type="checkbox" name="habilidades[]"
                           value="<?= (int)$h['id'] ?>"
                           <?= in_array((int)$h['id'], $marcadas, true) ? 'checked' : '' ?>>
                    <?= e($h['nome']) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <button type="submit">Cadastrar</button>
    </form>
</div>
</body>
</html>