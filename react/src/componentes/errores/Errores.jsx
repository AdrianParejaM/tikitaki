import React from "react";
import "./Errores.css";

const Errores = ({ children }) => {
  return (
    <>
      <div className='errores'>{children}</div>
    </>
  );
};

export default Errores;
