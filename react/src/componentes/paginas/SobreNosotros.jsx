import React from 'react';
import ig from '../../imagenes/ig.png';

const SobreNosotros = () => {
  return (
    <>
      <main className="min-h-screen flex flex-col items-center px-4 py-8 text-[#EEEEEE]">
        {/* Cabecera */}
        <header className="w-full max-w-5xl mb-8 text-center">
          <p className="text-[2rem] sm:text-[2.5rem] md:text-[3rem] lg:text-[3.5rem] font-extrabold tracking-wide text-[#7bb369] break-words">
            SOBRE&nbsp;NOSOTROS
          </p>
          <hr className="mt-2 h-1 w-32 mx-auto bg-[#046942] border-0 rounded" />
        </header>

        {/* Bloque misión */}
        <section className="w-full max-w-5xl mb-8 bg-[#7bb369]/20 rounded-lg shadow-lg p-6">
          <h2 className="text-2xl font-semibold text-[#7bb369] mb-2">Nuestra misión</h2>
          <p className="leading-relaxed">
            En <strong>Tiki&nbsp;Taki</strong> queremos revolucionar la forma en que los aficionados viven la Segunda División Española.
            Creamos una comunidad donde estrategia, pasión y diversión competitiva se unen para que te conviertas en el mánager de tu propio equipo virtual.
          </p>
        </section>

        {/* Bloque qué ofrecemos */}
        <section className="w-full max-w-5xl mb-8 bg-[#7bb369]/20 rounded-lg shadow-lg p-6">
          <h2 className="text-2xl font-semibold text-[#7bb369] mb-2">Qué ofrecemos</h2>
          <ul className="list-disc list-inside space-y-2">
            <li><span className="font-medium text-[#EEEEEE]">Mercado dinámico:</span> compra y venta de jugadores con valores actualizados.</li>
            <li><span className="font-medium text-[#EEEEEE]">Puntos en tiempo real:</span> basados en el rendimiento auténtico de cada jornada.</li>
            <li><span className="font-medium text-[#EEEEEE]">Ligas públicas y privadas:</span> compite contra amigos o únete a nuevas comunidades.</li>
            <li><span className="font-medium text-[#EEEEEE]">Estadísticas y noticias:</span> toda la información actual de la categoría de plata.</li>
          </ul>
        </section>

        {/* Bloque equipo */}
        <section className="w-full max-w-5xl mb-8 bg-[#7bb369]/20 rounded-lg shadow-lg p-6">
          <h2 className="text-2xl font-semibold text-[#7bb369] mb-2">Nuestro equipo</h2>
          <p className="leading-relaxed">
            Somos un grupo de desarrolladores y fanáticos del fútbol que sabe lo que significa madrugar para ver a tu club y refrescar la app en busca de fichajes.
            Construimos Tiki Taki para gente como tú.
          </p>
        </section>

        {/* Bloque contacto */}
        <section className="w-full max-w-5xl bg-[#046942] rounded-lg shadow-lg p-6 text-center">
          <h2 className="text-2xl font-semibold text-[#EEEEEE] mb-4">¿Quieres saber más?</h2>
          <p className="mb-6">
            Escríbenos a{" "}
            <a
              href="#"
              className="text-[#7bb369] underline decoration-[#7bb369] hover:text-[#7bb369]"
            >
              contacto@tikitaki.com
            </a>
            {" "}
            o síguenos en nuestra red social.
          </p>
          <div className="flex justify-center gap-6">
            <a
              href="https://www.instagram.com/tikitak1_/"
              target="_blank"
              rel="noopener noreferrer"
              className="w-10 h-10 rounded-full bg-[#7bb369] flex items-center justify-center font-bold hover:scale-110 transition-transform"
            >
              <img src={ig} alt="Instagram" />
            </a>
          </div>
        </section>
      </main>
    </>
  );
};

export default SobreNosotros;
