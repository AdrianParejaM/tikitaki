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
      <Menu />
      <Contenido>
        <Rutas />
      </Contenido>
      <Pie />
    </ProveedorSesion>
    </>
  )
}

export default App
