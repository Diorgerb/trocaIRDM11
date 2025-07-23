<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Como funciona? | Relação de Troca IRDM11 → IRIM11</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-start p-6">

  <nav class="w-full max-w-4xl bg-white rounded-xl shadow-md p-5 mt-8 mb-10 flex justify-center space-x-6 text-lg font-semibold">
    <a href="api/index.php" class="transition-colors duration-300 px-5 py-2 rounded-md
      <?= basename($_SERVER['PHP_SELF']) === 'api/index.php' ? 'text-blue-600 hover:text-blue-800' : 'text-gray-700 hover:text-blue-600' ?>">
      Simulador
    </a>
    <a href="api/como-funciona.php" class="transition-colors duration-300 px-5 py-2 rounded-md
      <?= basename($_SERVER['PHP_SELF']) === 'api/como-funciona.php' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-700 hover:text-blue-600' ?>">
      Como funciona
    </a>
  </nav>

  <main class="w-full max-w-4xl bg-white rounded-xl shadow-lg p-10 text-gray-800 leading-relaxed">
    <h1 class="text-4xl font-extrabold text-center text-blue-700 mb-10 tracking-tight">
      Como funciona?
    </h1>

    <section class="mb-8 space-y-5">
      <p class="text-lg">
        Este simulador foi criado para auxiliar cotistas a compreenderem de forma clara e transparente a relação de troca entre os fundos imobiliários <strong>IRDM11</strong> e <strong>IRIM11</strong>.
      </p>

      <p class="text-red-700 font-semibold text-lg bg-red-100 p-4 rounded-lg border border-red-300 shadow-inner">
        ⚠️ <strong>Atenção:</strong> Este simulador é apenas uma ferramenta informativa e <u>não constitui recomendação de compra, venda ou investimento</u>. Recomendamos sempre consultar um profissional financeiro qualificado antes de qualquer decisão.
      </p>

      <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 shadow-sm">
        <h2 class="text-2xl font-semibold text-blue-700 mb-4">Base do cálculo</h2>
        <p class="mb-4 text-lg text-gray-700">
          O cálculo realizado neste simulador segue rigorosamente os parâmetros descritos no relatório gerencial oficial da operação (disponibilizado em anexo). Dessa forma, o resultado representa a relação de troca considerando:
        </p>
        <ul class="list-disc list-inside space-y-2 text-lg text-gray-700">
          <li>A quantidade atual de cotas IRDM11 que o cotista possui.</li>
          <li>O preço médio de aquisição dessas cotas.</li>
          <li>As cotações atuais das cotas líquidas dos fundos IRDM11 e IRIM11.</li>
          <li>A cota referência oficial do IRDM11 usada para cálculo da amortização e imposto.</li>
        </ul>
      </div>

      <p class="text-lg">
        O resultado gerado indica a quantidade de cotas IRIM11 que o cotista receberá após a troca, o valor da amortização líquida após a dedução do imposto, além do cálculo do imposto sobre ganho de capital.
      </p>
    </section>

    <section class="mt-12 border-t pt-8 text-center max-w-sm mx-auto">
      <h2 class="text-3xl font-bold mb-5 text-blue-600">Me compre um café ☕</h2>
      <p class="mb-6 text-gray-700 text-lg leading-relaxed">
        Se você gostou do projeto, uma contribuição simbólica de <strong>R$0,99</strong> via Pix ajuda muito a manter e melhorar o desenvolvimento.
      </p>

      <a href="https://nubank.com.br/cobrar/26deo/6881655b-0c1a-45bf-bb1a-3ca0e53e8f84" target="_blank" 
         class="inline-block px-10 py-4 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-md shadow-md transition">
        Contribuir via Pix
      </a>
    </section>
  </main>

  <footer class="w-full max-w-4xl text-center text-gray-500 text-sm py-6 mt-20 border-t border-gray-300 select-none">
    © Diórger Bretas <?= date('Y') ?>. Todos os direitos reservados.
  </footer>

</body>
</html>
