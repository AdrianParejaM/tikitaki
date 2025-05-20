import React, { useState } from 'react';
import fotoJugador from '../../imagenes/FotoJugadorDefaultNoEntera.svg';

const jugadoresEnMercado = [
  { id: 1, nombre: "Santi Cazorla", valor: 12000000, foto: fotoJugador },
  { id: 2, nombre: "Rubén Castro", valor: 9000000, foto: fotoJugador },
  { id: 3, nombre: "Jonathan Viera", valor: 10500000, foto: fotoJugador },
  { id: 4, nombre: "Jesé Rodríguez", valor: 7000000, foto: fotoJugador },
];

const Mercado = () => {
  const [jugadorSeleccionado, setJugadorSeleccionado] = useState(null);
  const [puja, setPuja] = useState('');
  const [error, setError] = useState('');

  const abrirModal = (jugador) => {
    setJugadorSeleccionado(jugador);
    setPuja(jugador.valor.toString()); // precarga valor base
    setError('');
  };

  const cerrarModal = () => {
    setJugadorSeleccionado(null);
    setError('');
  };

  const manejarPuja = () => {
    const pujaNumerica = parseInt(puja);
    if (isNaN(pujaNumerica) || pujaNumerica < jugadorSeleccionado.valor) {
      setError(`La puja debe ser mayor o igual a ${jugadorSeleccionado.valor.toLocaleString()}€`);
      return;
    }

    // Aquí irá el envío a base de datos en el futuro
    cerrarModal();
  };

  return (
    <div className="w-full h-full bg-[#7bb369] flex flex-col items-center justify-start py-6 px-4">
      {/* Título */}
      <header className="w-full max-w-5xl mb-6 text-center">
        <h1 className="text-3xl sm:text-4xl font-extrabold text-white">
          MERCADO DE FICHAJES
        </h1>
        <hr className="mt-2 h-1 w-28 mx-auto bg-[#046942] border-0 rounded" />
      </header>

      {/* Lista de jugadores */}
      <section className="w-full max-w-5xl space-y-6">
        {jugadoresEnMercado.map((jugador) => (
          <div
            key={jugador.id}
            className="bg-[#046942] flex flex-col sm:flex-row items-center justify-between rounded-lg shadow-lg p-4 text-white"
          >
            <div className="flex items-center gap-6 w-full sm:w-auto">
              <div className="bg-[#7bb369] rounded-lg shadow">
                <img
                  src={jugador.foto}
                  alt={jugador.nombre}
                  className="w-40 h-40 object-contain rounded-md"
                />
              </div>
              <div>
                <h2 className="text-xl sm:text-2xl font-semibold">{jugador.nombre}</h2>
                <p className="text-l text-[#7bb369] mt-2">
                  Valor de mercado: <strong>{jugador.valor.toLocaleString()}€</strong>
                </p>
              </div>
            </div>
            <button
              onClick={() => abrirModal(jugador)}
              className="mt-4 sm:mt-0 bg-[#7bb369] text-[#046942] font-semibold px-4 py-2 rounded shadow hover:scale-105 transition-transform"
            >
              PUJAR
            </button>
          </div>
        ))}
      </section>

      {/* Modal de puja */}
      {jugadorSeleccionado && (
        <div className="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
          <div className="bg-white text-black p-6 rounded-lg shadow-lg w-80">
            <h2 className="text-xl font-semibold mb-4 text-center text-[#046942]">
              Pujar por {jugadorSeleccionado.nombre}
            </h2>
            <p className="mb-2 text-sm text-gray-600">
              Valor mínimo: <strong>{jugadorSeleccionado.valor.toLocaleString()}€</strong>
            </p>
            <input
              type="number"
              value={puja}
              onChange={(e) => setPuja(e.target.value)}
              placeholder="Introduce tu puja"
              className="w-full border border-gray-300 rounded px-3 py-2 mb-2"
              min={jugadorSeleccionado.valor}
            />
            {error && <p className="text-red-500 text-sm mb-2">{error}</p>}
            <div className="flex justify-between mt-4">
              <button
                onClick={cerrarModal}
                style={{ backgroundColor: 'rgb(230, 51, 51)', color: 'white' }}
                className="px-4 py-2 rounded hover:scale-105 transition-transform"
              >
                Cancelar
              </button>
              <button
                onClick={manejarPuja}
                className="bg-[#046942] text-white px-4 py-2 rounded hover:scale-105 transition-transform"
              >
                Confirmar
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default Mercado;
