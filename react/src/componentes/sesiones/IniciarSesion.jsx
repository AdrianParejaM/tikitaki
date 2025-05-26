import React from "react";
import useSesion from "../../hooks/useSesion.jsx";
import { Link } from "react-router-dom";

const IniciarSesion = () => {
  const { actualizarDato, iniciarSesion } = useSesion();

  return (
    <div className="flex flex-col items-center justify-center h-1/5">
      <h2 className="mt-20 text-4xl font-bold text-[#7bb369] mb-4">Iniciar sesión</h2>

      {/* Contenedor del formulario más ancho y sin espacio con los bordes */}
      <div className="w-full mt-5 max-w-xl p-0">
        <div className="mb-4 flex items-center">
          <label htmlFor="email" className="w-1/3 font-medium text-[#EEEEEE]">
            Correo electrónico
          </label>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Su correo electrónico..."
            onChange={actualizarDato}
            className="w-2/3 p-2 rounded-lg focus:outline-none focus:border-green-500 border border-gray-300"
          />
        </div>

        <div className="mb-6 flex items-center">
          <label htmlFor="password" className="w-1/3 font-medium text-[#EEEEEE]">
            Contraseña
          </label>
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Su contraseña..."
            onChange={actualizarDato}
            className="w-2/3 p-2 rounded-lg focus:outline-none focus:border-green-500 border border-gray-300"
          />
        </div>

        <p className="mb-4 text-[#EEEEEE] text-center">
          Si no tienes una cuenta{" "}
          <Link className="text-green-600 font-semibold hover:underline" to="/registro">
            regístrate
          </Link>
        </p>

        {/* Botón centrado */}
        <div className="flex justify-center">
          <button
            className="mt-6 bg-[#7bb369] text-white py-2 px-6 rounded-lg hover:bg-[#7AA369] transition"
            onClick={iniciarSesion}
          >
            Iniciar sesión
          </button>
        </div>
      </div>
    </div>
  );
};

export default IniciarSesion;
