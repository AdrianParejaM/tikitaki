import React from "react";
import "./Inicio.css";

const Inicio = () => {
  return (
    <>
      <h2 className="titulo_inicio">Tiki Taki</h2>
      <div className='inicio_columnas'>
        <p className='columna_1'>
        <em>CamisFutbol</em> es una innovadora aplicación dedicada exclusivamente a la venta de camisetas de fútbol personalizadas y oficiales. Diseñada para los verdaderos 
        fanáticos del deporte rey, esta plataforma ofrece una experiencia única donde puedes explorar un extenso catálogo de camisetas de tus equipos favoritos, 
        tanto nacionales como internacionales. Además, <em>CamisFutbol</em> permite personalizar las camisetas con nombres, números y detalles especiales, lo que la convierte 
        en el lugar perfecto para quienes desean lucir una prenda única y completamente adaptada a su pasión futbolística.
        </p>
        <p className='columna_2'>
        La app también destaca por su facilidad de uso y su enfoque en la comunidad futbolera. Con una interfaz intuitiva, los usuarios pueden buscar camisetas 
        por equipo, liga o jugador, acceder a ofertas exclusivas y recibir recomendaciones basadas en sus preferencias. Además, <em>CamisFutbol</em> se asegura de ofrecer 
        productos 100% originales y de alta calidad, respaldados por un sistema de compra seguro y opciones de envío rápido. Ya sea que busques la última camiseta 
        de tu equipo favorito o un regalo especial para un amigo futbolero, <em>CamisFutbol</em> es la solución ideal para los amantes del fútbol y la moda deportiva.
        </p>
      </div>
    </>
  );
};

export default Inicio;
