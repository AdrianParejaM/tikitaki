import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import "./Cabecera.css";
import useSesion from "../../hooks/useSesion.jsx";
import CerrarSesion from "../sesiones/CerrarSesion.jsx";
import Logo from '../../imagenes/Logo_tiki_taki.svg';
import menuHamb from '../../imagenes/menuhamb.png';

const Cabecera = () => {
  const { sesionIniciada, datosSesion } = useSesion();
  const [menuAbierto, setMenuAbierto] = useState(false);
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768);

  const toggleMenu = () => setMenuAbierto(!menuAbierto);

  useEffect(() => {
    const handleResize = () => {
      setIsMobile(window.innerWidth < 768);
      if (window.innerWidth >= 768) {
        setMenuAbierto(false);
      }
    };

    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  return (
    <div id='cabecera_navegacion' className="relative w-full">
      <header id='cabecera' className="flex items-center justify-between px-4 py-2">
        {/* Logo solo en pantallas grandes */}
        <img src={Logo} alt="Logo app" height="100px" width="100px" className="hidden md:block" />

        {/* Icono de menú hamburguesa en pantallas pequeñas */}
        <img
          src={menuHamb}
          alt="Menu"
          className="md:hidden w-10 h-10 cursor-pointer transition-colors duration-300"
          onClick={toggleMenu}  // sin delay
          style={{ backgroundColor: menuAbierto ? 'rgba(255, 0, 0, 0.2)' : 'transparent' }}
        />

        {/* Contenedor para usuario y cerrar sesión */}
        <div className="flex items-center gap-2">
          {/* Info de usuario */}
          {sesionIniciada && datosSesion?.email && (
            <Link to='/perfil' className="text-sm text-white md:text-base">
              <p className="nombre_usuario md:text-sm lg:text-base hidden md:flex">Hola, {datosSesion.email}</p>
            </Link>
          )}

          {/* Botón de sesión */}
          <nav className='navegacion_menu'>
            {!sesionIniciada ? (
              <Link className='enlace_login text-sm' to='/login'>Login</Link>
            ) : (
              <div className="text-sm">
                <CerrarSesion />
              </div>
            )}
          </nav>
        </div>
      </header>

      {/* Menú hamburguesa desplegable */}
      {menuAbierto && (
        <div className="fixed top-0 left-0 w-full h-full bg-[#272727] text-white flex flex-col items-center justify-center space-y-6 z-50 md:hidden transition-opacity duration-300">
          <button 
            onClick={toggleMenu}  // sin delay
            className="absolute top-4 right-4 text-2xl bg-red-600 rounded-full w-10 h-10 flex items-center justify-center transition-colors duration-300"
            style={{ lineHeight: '1' }}
          >
            ×
          </button>
          <Link to='/' className='navegacion__enlace text-xl' onClick={toggleMenu}>Inicio</Link>
          <Link to='/plantilla' className='navegacion__enlace text-xl' onClick={toggleMenu}>Plantilla</Link>
          <Link to='/mercado' className='navegacion__enlace text-xl' onClick={toggleMenu}>Mercado</Link>
          <Link to='/clasificacion' className='navegacion__enlace text-xl' onClick={toggleMenu}>Clasificación</Link>
          <Link to='/sobrenosotros' className='navegacion__enlace text-xl' onClick={toggleMenu}>Sobre Nosotros</Link>
          <Link to='/perfil' className="navegacion__enlace text-xl" onClick={toggleMenu}>Perfil</Link>
        </div>
      )}
    </div>
  );
};

export default Cabecera;
