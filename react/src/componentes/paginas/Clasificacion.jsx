import React from 'react';

const Clasificacion = () => {

  // Ejemplo de datos (puedes reemplazarlo por props o datos del backend)
  const nombreLiga = "Liga Élite TikiTaki";
  const clasificacion = [
    { usuario: "AdrianElCrack", puntos: 169 },
    { usuario: "Cristine", puntos: 133 },
    { usuario: "Barça", puntos: 129 },
    { usuario: "Eldense", puntos: 118 },
    { usuario: "Real de Madri", puntos: 1 },
  ];

  return (
    <>
     <div className="flex flex-col items-center rounded-lg shadow-md w-7/8 mx-auto my-8 p-8  bg-[#7bb369] text-[#EEEEEE]">

      {/* Cabecera de la liga */}
      <header className="w-full max-w-3xl mb-8 text-center">
        <h1 className="text-[2rem] sm:text-[2.5rem] md:text-[3rem] font-extrabold tracking-wide text-white break-words">
          {nombreLiga.toUpperCase()}
        </h1>
        <hr className="mt-2 h-1 w-32 mx-auto bg-[#046942] border-0 rounded" />
      </header>

      {/* Tabla de clasificación */}
      <section className="w-full max-w-3xl bg-[#046942] rounded-lg shadow-lg overflow-hidden">
        <table className="w-full text-left">
          <thead className="bg-[#035336] text-[#7bb369] text-sm uppercase tracking-wider">
            <tr>
              <th className="px-4 py-3">Posición</th>
              <th className="px-4 py-3">Usuario</th>
              <th className="px-4 py-3 text-right">Puntos</th>
            </tr>
          </thead>
          <tbody className="text-[#EEEEEE]">
            {clasificacion.map((jugador, index) => (
              <tr
                key={index}
                className={`${
                  index % 2 === 0 ? 'bg-[#7bb369]/10' : 'bg-[#7bb369]/20'
                } hover:bg-[#7bb369]/30 transition`}
              >
                <td className="px-4 py-3 font-semibold">{index + 1}</td>
                <td className="px-4 py-3">{jugador.usuario}</td>
                <td className="px-4 py-3 text-right">{jugador.puntos}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </section>
    </div>
    </>
  );
};

export default Clasificacion;