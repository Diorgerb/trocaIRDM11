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
<body class="bg-gradient-to-tr from-blue-50 to-white min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg p-8">
    <h1 class="text-3xl font-extrabold text-center text-blue-800 mb-8">RELAÇÃO DE TROCA: IRDM11 → IRIM11</h1>

    <?php if ($erro): ?>
      <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md shadow-sm">
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>

    <?php if ($resultado): ?>
      <div class="mb-8 p-6 bg-green-50 border border-green-300 rounded-lg shadow-inner text-gray-900 leading-relaxed space-y-4">
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
      </div>
    <?php endif; ?>

    <form method="post" class="space-y-6" novalidate>
      <div>
        <label for="quantidade" class="block text-gray-700 font-semibold mb-2">Quantidade de cotas IRDM11</label>
        <input id="quantidade" name="quantidade" type="number" min="1" required
          class="w-full rounded border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
          value="<?= isset($quantidade) ? htmlspecialchars($quantidade) : '' ?>" />
      </div>

      <div>
        <label for="preco_medio" class="block text-gray-700 font-semibold mb-2">Preço médio de aquisição (R$)</label>
        <input id="preco_medio" name="preco_medio" type="number" step="0.01" min="0.01" required
          class="w-full rounded border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
          value="<?= isset($preco_medio) ? htmlspecialchars($preco_medio) : '' ?>" />
      </div>

      <div>
        <label for="cota_referencia_irdm" class="block text-gray-700 font-semibold mb-2">Cota Referência IRDM11 (R$)</label>
        <input id="cota_referencia_irdm" name="cota_referencia_irdm" type="number" step="0.01" min="0.01" required
          class="w-full rounded border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
          value="<?= isset($cota_referencia_irdm) ? htmlspecialchars($cota_referencia_irdm) : '81.18' ?>" />
      </div>

      <div>
        <label for="cota_liquida_irdm" class="block text-gray-700 font-semibold mb-2">Cota Líquida IRDM11 (R$)</label>
        <input id="cota_liquida_irdm" name="cota_liquida_irdm" type="number" step="0.01" min="0.01" required
          class="w-full rounded border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
          value="<?= isset($cota_liquida_irdm) ? htmlspecialchars($cota_liquida_irdm) : '76.45' ?>" />
      </div>

      <div>
        <label for="cota_liquida_irim" class="block text-gray-700 font-semibold mb-2">Cota Líquida IRIM11 (R$)</label>
        <input id="cota_liquida_irim" name="cota_liquida_irim" type="number" step="0.01" min="0.01" required
          class="w-full rounded border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
          value="<?= isset($cota_liquida_irim) ? htmlspecialchars($cota_liquida_irim) : '84.47' ?>" />
      </div>

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors duration-300">
        Calcular
      </button>
    </form>


<div class="mt-6 text-center">
  <a href="https://fnet.bmfbovespa.com.br/fnet/publico/exibirDocumento?id=948175&cvm=true" 
     target="_blank" rel="noopener noreferrer"
     class="text-blue-600 hover:text-blue-800 underline font-semibold">
    📄 Clique aqui para acessar o Fato Relevante oficial sobre a operação
  </a>
</div>


  </div>

</body>
</html>
