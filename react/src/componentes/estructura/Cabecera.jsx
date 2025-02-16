import React from "react";
import { Link } from "react-router-dom";
import "./Cabecera.css";
import useSesion from "../../hooks/useSesion.jsx";
import CerrarSesion from "../sesiones/CerrarSesion.jsx";
import Logo from '../../imagenes/Logo_tiki_taki.svg';

const Cabecera = () => {
  const { sesionIniciada, datosSesion } = useSesion();

  return (
    <>
      <div id='cabecera_navegacion'>
        <header id='cabecera'>
          <img src={Logo} alt="Logo app" height="100px" width="100px"/>
          <div className='cabecera_sesion'></div>
        </header>

        {sesionIniciada && datosSesion && datosSesion.email ? (
          <Link to='/perfil'>
            <p className="nombre_usuario">Hola, {datosSesion.email}</p>
          </Link>
        ) : null}

        <nav className='navegacion_menu'>
          {!sesionIniciada ? (
            <Link className='enlace_login' to='/login'>
              Login
            </Link>
          ) : (
            <CerrarSesion />
          )}
        </nav>
      </div>
    </>
  );
};

export default Cabecera;
