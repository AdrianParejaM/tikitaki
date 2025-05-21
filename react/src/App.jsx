import './App.css';
import ProveedorSesion from './contextos/ProveedorSesion.jsx';
import Cabecera from './componentes/estructura/Cabecera.jsx';
import Menu from './componentes/estructura/Menu.jsx';
import Contenido from './componentes/estructura/Contenido.jsx';
import Rutas from './componentes/rutas/Rutas.jsx';
import Pie from './componentes/estructura/Pie.jsx';

function App() {
  return (
    <>
      <ProveedorSesion>
        <Cabecera />
        {/* Menú de desktop con fondo #272727 */}
        <div className="hidden md:block bg-[#1f1f1f] b-0px">
          <Menu />
        </div>
        <Contenido>
          <Rutas />
        </Contenido>
        <Pie />
      </ProveedorSesion>
    </>
  )
}

export default App;