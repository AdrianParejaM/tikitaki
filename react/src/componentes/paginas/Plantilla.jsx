import React from 'react';
import campoFutbol from '../../imagenes/campoFutbol.png';
import fotoJugador from '../../imagenes/FotoJugadorDefaultNoEntera.svg';

const Plantilla = () => {
  const jugadoresDummy = ["Jugador 1", "Jugador 2", "Jugador 3"];

  const renderJugador = (top, left, keyPrefix) => (
    <div
      key={`${keyPrefix}-${left}`}
      className="absolute flex flex-col items-center transform -translate-x-1/2 -translate-y-1/2"
      style={{ top: `${top}%`, left: `${left}%` }}
    >
      <img
        src={fotoJugador}
        alt="jugador"
        className="w-24 md:w-28 lg:w-28 xl:w-28 drop-shadow-lg object-contain"
      />
      <select className="mt-1 bg-white text-black text-sm rounded px-2 py-1 shadow w-28 text-center">
        {jugadoresDummy.map((nombre, i) => (
          <option key={i} value={nombre}>{nombre}</option>
        ))}
      </select>
    </div>
  );

  return (
    <div className="flex flex-col items-center rounded-lg shadow-md w-7/8 mx-auto my-8 p-8 bg-[#7bb369]">
      <h1 className="text-4xl font-extrabold text-white  drop-shadow-lg">
        Mi Plantilla
      </h1>
      <hr className="mt-2 h-1 w-32 mx-auto bg-[#046942] border-0 rounded mb-6" />

      <div className="mb-4">
        <select
          className="bg-[#046942] text-white px-4 py-2 rounded shadow"
          defaultValue="4-3-3"
        >
          <option value="4-3-3">4-3-3</option>
        </select>
      </div>

      <div
        className="relative w-full max-w-md aspect-[3/4] bg-cover bg-center rounded-xl shadow-lg"
        style={{ backgroundImage: `url(${campoFutbol})` }}
      >
        {/* Portero */}
        {renderJugador(85, 50, 'gk')}

        {/* Defensas */}
        {renderJugador(60, 20, 'df')}
        {renderJugador(65, 38, 'df')}
        {renderJugador(65, 62, 'df')}
        {renderJugador(60, 80, 'df')}

        {/* Mediocentros */}
        {renderJugador(40, 30, 'mid')}
        {renderJugador(35, 50, 'mid')}
        {renderJugador(40, 70, 'mid')}

        {/* Delanteros */}
        {renderJugador(20, 25, 'fw')}
        {renderJugador(15, 50, 'fw')}
        {renderJugador(20, 75, 'fw')}
      </div>

      <button className="mt-6 bg-[#046942] text-white px-6 py-2 rounded shadow hover:scale-105 transition-transform">
        Guardar
      </button>
    </div>
  );
};

export default Plantilla;
