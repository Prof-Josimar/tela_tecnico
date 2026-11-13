<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesquisar Técnico</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f3f4f6; margin:0; padding:24px; }
        .card { max-width:760px; margin:auto; background:#fff; padding:24px; border-radius:8px; }
        #busca { width:100%; padding:12px; font-size:16px; box-sizing:border-box; }
        .item { border:1px solid #e5e7eb; border-radius:6px; padding:12px; margin-top:12px; }
        .item h3 { margin:0 0 6px; }
        .item p { margin:2px 0; color:#374151; }
        .tag {
            display:inline-block; background:#dbeafe; color:#1e40af;
            padding:2px 8px; border-radius:12px; font-size:13px; margin:4px 4px 0 0;
        }
        #status { margin-top:12px; color:#6b7280; }
    </style>
</head>
<body>
<div class="card">
    <p><a href="index.php">&larr; Voltar ao menu</a></p>
    <h2>Pesquisar Técnico</h2>

    <input type="text" id="busca" placeholder="Digite nome, telefone, e-mail ou habilidade..." autofocus autocomplete="off">
    <div id="status"></div>
    <div id="resultados"></div>
</div>

<script>
const input      = document.getElementById('busca');
const resultados = document.getElementById('resultados');
const status     = document.getElementById('status');

let timer = null;
let controller = null;

function el(tag, text, cls) {
    const n = document.createElement(tag);
    if (text !== undefined) n.textContent = text;   // textContent evita XSS
    if (cls) n.className = cls;
    return n;
}

function render(lista) {
    resultados.innerHTML = '';
    status.textContent = lista.length
        ? lista.length + ' técnico(s) encontrado(s)'
        : 'Nenhum técnico encontrado.';

    lista.forEach(t => {
        const box = el('div', undefined, 'item');
        box.appendChild(el('h3', t.nome));
        box.appendChild(el('p', 'Endereço: ' + t.endereco));

        const tels = [t.telefone1, t.telefone2].filter(Boolean).join(' / ');
        box.appendChild(el('p', 'Telefone(s): ' + tels));
        box.appendChild(el('p', 'E-mail: ' + t.email));

        if (t.habilidades) {
            const wrap = el('div');
            t.habilidades.split(', ').forEach(h => wrap.appendChild(el('span', h, 'tag')));
            box.appendChild(wrap);
        }
        resultados.appendChild(box);
    });
}

async function pesquisar(termo) {
    if (controller) controller.abort();      // cancela a requisição anterior
    controller = new AbortController();

    try {
        status.textContent = 'Buscando...';
        const resp = await fetch('buscar.php?q=' + encodeURIComponent(termo), {
            signal: controller.signal
        });
        if (!resp.ok) throw new Error('HTTP ' + resp.status);
        render(await resp.json());
    } catch (err) {
        if (err.name === 'AbortError') return;
        status.textContent = 'Erro ao pesquisar. Tente novamente.';
    }
}

input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(() => pesquisar(input.value.trim()), 300); // debounce
});

pesquisar(''); // já carrega a lista inicial
</script>
</body>
</html>