import Head from 'next/head'
import Link from 'next/link'
import { useState, useEffect } from 'react'

export default function Home() {
  const [precoMedio, setPrecoMedio] = useState('')
  const [resultado, setResultado] = useState(null)

  const calcularTroca = () => {
    setResultado({
      cotas: 12,
      total: 1104
    })
  }

  return (
    <div className="min-h-screen bg-gray-100 pb-20">
      <Head>
        <title>Simulador IRDM11 ↔ IRIM11</title>
      </Head>

      <header className="fixed top-0 left-0 right-0 bg-white shadow-md z-10 p-4 text-center">
        <h1 className="text-xl font-bold">💹 Simulador de Troca IRDM11 ↔ IRIM11</h1>
      </header>

      <main className="pt-24 px-4">
        <div className="bg-white rounded-2xl shadow p-6 w-full max-w-md mx-auto">
          <h2 className="text-lg font-semibold mb-4">Informe seu preço médio:</h2>
          <input
            type="number"
            value={precoMedio}
            onChange={(e) => setPrecoMedio(e.target.value)}
            className="w-full border rounded p-2 mb-4"
            placeholder="Ex: 92.50"
          />
          <button
            onClick={calcularTroca}
            className="w-full bg-blue-600 text-white font-medium px-4 py-2 rounded-xl hover:bg-blue-700"
          >
            Calcular troca
          </button>
        </div>

        {resultado && (
          <div className="grid grid-cols-2 gap-4 mt-6 max-w-md mx-auto">
            <div className="p-4 border rounded-xl bg-green-50 text-center">
              <h3 className="text-sm font-medium">Você receberá</h3>
              <p className="text-2xl font-bold">{resultado.cotas} cotas</p>
            </div>
            <div className="p-4 border rounded-xl bg-yellow-50 text-center">
              <h3 className="text-sm font-medium">Valor total</h3>
              <p className="text-2xl font-bold">R$ {resultado.total.toFixed(2)}</p>
            </div>
          </div>
        )}
      </main>

      <footer className="fixed bottom-0 left-0 right-0 bg-white border-t p-2 flex justify-around text-sm">
        <Link href="/">Simulador</Link>
        <Link href="/como-funciona">Como funciona</Link>
      </footer>
    </div>
  )
}