import React from "react";
import useSesion from "../../hooks/useSesion.jsx";
import { Link } from "react-router-dom";

const IniciarSesion = () => {
  const { actualizarDato, iniciarSesion } = useSesion();

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
      <h2 className="text-2xl font-bold mb-6">Iniciar sesión</h2>

      <div className="w-full max-w-md">
        <label htmlFor="email" className="block mb-2 font-medium text-gray-700">
          Correo electrónico
        </label>
        <input
          type="email"
          name="email"
          id="email"
          placeholder="Su correo electrónico."
          onChange={actualizarDato}
          className="w-full p-3 mb-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
        />

        <label htmlFor="password" className="block mb-2 font-medium text-gray-700">
          Contraseña
        </label>
        <input
          type="password"
          name="password"
          id="password"
          placeholder="Su contraseña."
          onChange={actualizarDato}
          className="w-full p-3 mb-6 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
        />
      </div>

      <p className="mb-6 text-gray-600">
        Si no tienes una cuenta{" "}
        <Link className="text-blue-500 hover:underline" to="/registro">
          regístrate
        </Link>
      </p>

      <button
        className="w-full max-w-md bg-blue-500 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        onClick={iniciarSesion}
      >
        Iniciar sesión
      </button>
    </div>
  );
};

export default IniciarSesion;
