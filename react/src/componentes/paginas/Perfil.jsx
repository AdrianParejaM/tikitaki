import React from "react";
import useSesion from "../../hooks/useSesion.jsx";
import "./Perfil.css";

const Perfil = () => {

    const { datosSesion } = useSesion();

  return (
    <>
        <h1 className="bienvenida_usuario">Bienvenido a tu perfil, {datosSesion.email}</h1>
        <div className="info_usuario">
            <h3>Aquí encontrarás toda tu información.</h3>
        </div>
    </>
  );
};

export default Perfil;