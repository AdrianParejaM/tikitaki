import React from "react";
import "./Login.css";
//import useSesion from "../../hooks/useSesion.jsx";
import IniciarSesion from "../sesiones/IniciarSesion.jsx";
import Errores from "../errores/Errores.jsx";

const Login = () => {

  //const { errorUsuario } = useSesion();

  return (
    <>
      <div className='inicioSesion'>
        <IniciarSesion />
      </div>
      <div className="error">
        <Errores>{/*errorUsuario*/}</Errores>
      </div>
    </>
  );
};

export default Login;
