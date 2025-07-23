<?php
function formatar_valor($valor, $decimais = 2) {
    return number_format($valor, $decimais, ',', '.');
}

$erro = null;
$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $preco_medio = filter_input(INPUT_POST, 'preco_medio', FILTER_VALIDATE_FLOAT);
    $cota_liquida_irdm = filter_input(INPUT_POST, 'cota_liquida_irdm', FILTER_VALIDATE_FLOAT);
    $cota_liquida_irim = filter_input(INPUT_POST, 'cota_liquida_irim', FILTER_VALIDATE_FLOAT);
    $cota_referencia_irdm = filter_input(INPUT_POST, 'cota_referencia_irdm', FILTER_VALIDATE_FLOAT);

    if (!$quantidade) {
        $erro = "Informe uma quantidade válida.";
    } elseif (!$preco_medio || $preco_medio <= 0) {
        $erro = "Informe um preço médio válido.";
    } elseif (!$cota_referencia_irdm || $cota_referencia_irdm <= 0) {
        $erro = "Informe a Cota Referência IRDM11 válida.";
    } elseif (!$cota_liquida_irdm || $cota_liquida_irdm <= 0) {
        $erro = "Informe a Cota Líquida IRDM11 válida.";
    } elseif (!$cota_liquida_irim || $cota_liquida_irim <= 0) {
        $erro = "Informe a Cota Líquida IRIM11 válida.";
    } else {
        // Cálculos
        $amortizacao_bruta = $cota_referencia_irdm - $cota_liquida_irdm;

        $base_imposto = $cota_referencia_irdm - $preco_medio;
        $imposto_por_cota = ($base_imposto > 0) ? $base_imposto * 0.20 : 0;

        $amortizacao_liquida = max(0, $amortizacao_bruta - $imposto_por_cota);

        $valor_amortizacao_total = $amortizacao_liquida * $quantidade;

        $relacao_troca = $cota_liquida_irdm / $cota_liquida_irim;

        $quantidade_irim = $quantidade * $relacao_troca;

        $resultado = [
            'quantidade' => $quantidade,
            'preco_medio' => formatar_valor($preco_medio),
            'cota_liquida_irdm' => formatar_valor($cota_liquida_irdm),
            'cota_liquida_irim' => formatar_valor($cota_liquida_irim),
            'cota_referencia_irdm' => formatar_valor($cota_referencia_irdm),
            'relacao_troca' => formatar_valor($relacao_troca, 4),
            'imposto_por_cota' => formatar_valor($imposto_por_cota),
            'amortizacao_bruta' => formatar_valor($amortizacao_bruta),
            'amortizacao_liquida' => formatar_valor($amortizacao_liquida),
            'valor_amortizacao_total' => formatar_valor($valor_amortizacao_total),
            'quantidade_irim' => $quantidade_irim,
            'quantidade_irim_inteira' => floor($quantidade_irim),
            'quantidade_irim_fracionada' => $quantidade_irim - floor($quantidade_irim),
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Relação de troca: IRDM11 → IRIM11</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-start p-6">

  <nav class="w-full max-w-4xl bg-white rounded-xl shadow-md p-5 mt-8 mb-10 flex justify-center space-x-6 text-lg font-semibold">
    <a href="index.php" class="transition-colors duration-300 px-5 py-2 rounded-md
      <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:text-blue-600' ?>">
      Simulador
    </a>
    <a href="como-funciona.php" class="transition-colors duration-300 px-5 py-2 rounded-md
      <?= basename($_SERVER['PHP_SELF']) === 'como-funciona.php' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:text-blue-600' ?>">
      Como funciona
    </a>
  </nav>

  <main class="w-full max-w-4xl bg-white rounded-xl shadow-lg p-10 text-gray-800 leading-relaxed">
    <h1 class="text-4xl font-extrabold text-center text-blue-700 mb-10 tracking-tight">
      Relação de troca: IRDM11 → IRIM11
    </h1>

    <?php if ($erro): ?>
      <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md shadow-sm">
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>

    <?php if ($resultado): ?>
      <section class="mb-10 p-6 bg-green-50 border border-green-300 rounded-lg shadow-inner text-gray-900 leading-relaxed space-y-4">
        <p class="text-lg font-semibold">
          Relação de Troca = Cota Líquida IRDM ÷ Cota Líquida IRIM = 
          <span class="text-blue-700">R$ <?= $resultado['cota_liquida_irdm'] ?></span> ÷ 
          <span class="text-blue-700">R$ <?= $resultado['cota_liquida_irim'] ?></span> = 
          <span class="font-bold text-xl text-green-700"><?= $resultado['relacao_troca'] ?></span>
        </p>
        <p class="text-md font-semibold">• 1 Cota do IRDM será equivalente a <span class="text-green-700 font-bold"><?= $resultado['relacao_troca'] ?></span> cotas do IRIM</p>
        <p class="text-md">
          • Exemplo: Cotista que detém <span class="font-semibold"><?= $resultado['quantidade'] ?></span> cotas do IRDM, receberia 
          <span class="font-semibold text-green-700">R$ <?= $resultado['valor_amortizacao_total'] ?></span> (amortização e rendimento) e 
          <span class="font-semibold"><?= $resultado['quantidade_irim_inteira'] ?></span> cotas inteiras e 
          <span class="font-semibold"><?= number_format($resultado['quantidade_irim_fracionada'], 2, ',', '.') ?></span> fracionadas do IRIM
        </p>
        <hr class="border-green-300 my-4" />
        <p><strong>Detalhes do cálculo:</strong></p>
        <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
          <li>Amortização bruta por cota: R$ <?= $resultado['amortizacao_bruta'] ?></li>
          <li>Imposto por cota (20% sobre ganho de capital): R$ <?= $resultado['imposto_por_cota'] ?></li>
          <li>Amortização líquida por cota (bruta - imposto): R$ <?= $resultado['amortizacao_liquida'] ?></li>
        </ul>
      </section>
    <?php endif; ?>

    <form method="post" class="space-y-8 w-full" novalidate>
      <div>
        <label for="quantidade" class="block text-gray-700 font-semibold mb-2">Quantidade de cotas IRDM11</label>
        <input id="quantidade" name="quantidade" type="number" min="1" required
          class="w-full rounded border border-gray-300 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
          value="<?= isset($quantidade) ? htmlspecialchars($quantidade) : '' ?>" />
      </div>

      <div>
        <label for="preco_medio" class="block text-gray-700 font-semibold mb-2">Preço médio de aquisição (R$)</label>
        <input id="preco_medio" name="preco_medio" type="number" step="0.01" min="0.01" required
          class="w-full rounded border border-gray-300 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
          value="<?= isset($preco_medio) ? htmlspecialchars($preco_medio) : '' ?>" />
      </div>

      <div>
        <label for="cota_referencia_irdm" class="block text-gray-700 font-semibold mb-2">Cota Referência IRDM11 (R$)</label>
        <input id="cota_referencia_irdm" name="cota_referencia_irdm" type="number" step="0.01" min="0.01" required
          class="w-full rounded border border-gray-300 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
          value="<?= isset($cota_referencia_irdm) ? htmlspecialchars($cota_referencia_irdm) : '81.18' ?>" />
      </div>

      <div>
        <label for="cota_liquida_irdm" class="block text-gray-700 font-semibold mb-2">Cota Líquida IRDM11 (R$)</label>
        <input id="cota_liquida_irdm" name="cota_liquida_irdm" type="number" step="0.01" min="0.01" required
          class="w-full rounded border border-gray-300 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
          value="<?= isset($cota_liquida_irdm) ? htmlspecialchars($cota_liquida_irdm) : '76.45' ?>" />
      </div>

      <div>
        <label for="cota_liquida_irim" class="block text-gray-700 font-semibold mb-2">Cota Líquida IRIM11 (R$)</label>
        <input id="cota_liquida_irim" name="cota_liquida_irim" type="number" step="0.01" min="0.01" required
          class="w-full rounded border border-gray-300 px-5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg"
          value="<?= isset($cota_liquida_irim) ? htmlspecialchars($cota_liquida_irim) : '84.47' ?>" />
      </div>

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-lg transition-colors duration-300 text-xl">
        Calcular
      </button>
    </form>

    <div class="mt-14 text-center max-w-sm mx-auto">
      <h2 class="text-3xl font-bold mb-6 text-blue-600">Me compre um café ☕</h2>
      <p class="mb-8 text-gray-700 text-lg leading-relaxed">
        Se gostou do projeto, uma contribuição simbólica de <strong>R$0,99</strong> via Pix ajuda muito a manter e melhorar o desenvolvimento.
      </p>

      <a href="https://nubank.com.br/cobrar/26deo/6881655b-0c1a-45bf-bb1a-3ca0e53e8f84" target="_blank" 
         class="inline-block px-10 py-4 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-md shadow-md transition">
        Contribuir via Pix
      </a>
    </div>

  </main>

  <footer class="w-full max-w-4xl text-center text-gray-500 text-sm py-6 mt-20 border-t border-gray-300 select-none">
    © Diórger Bretas <?= date('Y') ?>. Todos os direitos reservados.
  </footer>

</body>
</html>
