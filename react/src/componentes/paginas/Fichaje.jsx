import React from 'react';
import fotoJugador from '../../imagenes/FotoJugadorDefaultNoEntera.svg';

const Fichaje = () => {
  return (
    <div className="bg-[#7bb369] p-8 rounded-lg shadow-md w-7/8 mx-auto my-8">
      <h1 className="bg-[#046942] text-white text-center text-[#eeeeee] py-4 text-3xl font-bold rounded-t-lg">ÚLTIMOS FICHAJES</h1>
      <div className="flex items-center p-8">
        <img src={fotoJugador} alt="Foto Jugador" className="w-100 h-64" />
        <div className="ml-8">
          <p className="text-[#eeeeee] text-2xl">
            <strong>Santiago Cazorla</strong> ha sido transferido de <strong>Equipo1</strong> a <strong>Equipo2</strong> por <strong>15.000.000€</strong>.
          </p>
          <p className="text-[#eeeeee] mt-3 text-xl">Valor Santiago Cazorla: <strong>12.000.000€</strong></p>
        </div>
      </div>
    </div>
  );
};

export default Fichaje;
