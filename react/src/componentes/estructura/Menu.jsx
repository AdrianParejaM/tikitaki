import React from "react";
import { Link } from "react-router-dom";
import useSesion from "../../hooks/useSesion.jsx";
import "./Menu.css";

const Menu = () => {
  const { sesionIniciada } = useSesion();

  return (
    <nav className='navegacion__menu p-2'>
      <Link className='navegacion__enlace inicio' to='/'>
        Inicio
      </Link>

      {sesionIniciada && (
        <>
          <Link className='navegacion__enlace' to='/plantilla'>
            Plantilla
          </Link>

          <Link className='navegacion__enlace' to='/mercado'>
            Mercado
          </Link>

          <Link className='navegacion__enlace' to='/clasificacion'>
            Clasificación
          </Link>

          <Link className='navegacion__enlace' to='/sobrenosotros'>
            Sobre Nosotros
          </Link>
        </>
      )}
    </nav>
  );
};

export default Menu;