import React, { useState } from "react";
import useSesion from "../../hooks/useSesion.jsx";

const CrearCuenta = () => {
  const { crearCuenta, actualizarDato } = useSesion();
  const [error, setError] = useState("");

  return (
    <div className="cuentaUsuario">
      <h2>Crea una nueva cuenta</h2>

      <label htmlFor="nickname">Nombre de usuario</label>
      <input
        type="text"
        name="nickname"
        id="nickname"
        placeholder="Su nombre de usuario..."
        onChange={actualizarDato}
      />

      <label htmlFor="name">Nombre</label>
      <input
        type="text"
        name="name"
        id="name"
        placeholder="Su nombre..."
        onChange={actualizarDato}
      />

      <label htmlFor="email">Correo electrónico</label>
      <input
        type="email"
        name="email"
        id="email"
        placeholder="Su correo electrónico..."
        onChange={actualizarDato}
      />

      <label htmlFor="password">Contraseña</label>
      <input
        type="password"
        name="password"
        id="password"
        placeholder="Su contraseña..."
        onChange={actualizarDato}
      />

      {error && <p style={{ color: "red" }}>{error}</p>}

      <button className="botonSesion" onClick={crearCuenta}>
        Crear cuenta
      </button>
    </div>
  );
};

export default CrearCuenta;
