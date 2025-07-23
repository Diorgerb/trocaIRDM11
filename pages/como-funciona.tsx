export default function ComoFunciona() {
  return (
    <div className="max-w-3xl mx-auto p-6 pt-24">
      <h1 className="text-2xl font-bold mb-4">Como funciona?</h1>
      <p className="mb-4 text-justify">
        Este simulador consulta as cotações atuais dos FIIs IRDM11 e IRIM11 em tempo real através do Yahoo Finance.
        Ao inserir seu preço médio de compra, calculamos quantas cotas do outro FII você conseguiria comprar caso vendesse tudo hoje.
      </p>
      <p className="mb-4 text-justify">
        A troca é apenas uma simulação. Não considera taxas, tributação ou liquidez de mercado.
      </p>
      <p className="text-sm text-gray-500">
        Desenvolvido por Diórger Bretas | Versão 1.0
      </p>
    </div>
  )
}