import React, { useState, useEffect, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { contextoSesion } from "../../contextos/ProveedorSesion.jsx";

const Clasificacion = () => {
  const navigate = useNavigate();
  const { usuario, sesionIniciada } = useContext(contextoSesion);
  const [liga, setLiga] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  // Verificar datos del usuario y token
  useEffect(() => {
    console.log("Datos de contexto:", { usuario, sesionIniciada });
    console.log("Token en localStorage:", localStorage.getItem("token"));
  }, [usuario, sesionIniciada]);

  // Obtener liga del usuario
  const fetchLigaUsuario = async () => {
    try {
      setLoading(true);
      setError(null);
      const token = localStorage.getItem("token");
      
      if (!token) {
        throw new Error("No hay token de autenticación");
      }

      const response = await fetch("http://localhost:8086/api/leagues", {
        headers: {
          "Authorization": `Bearer ${token}`,
          "Accept": "application/json"
        }
      });
      
      if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
      }
      
      const data = await response.json();
      console.log("Ligas obtenidas:", data);
      
      if (Array.isArray(data)) {
        const ligaUsuario = data.find(l => l.user_id === usuario?.id);
        if (ligaUsuario) setLiga(ligaUsuario);
      }
    } catch (err) {
      console.error("Error al obtener liga:", err);
      setError("No se pudo cargar la información de la liga");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (!sesionIniciada) {
      navigate("/login");
      return;
    }

    if (usuario?.id) {
      fetchLigaUsuario();
    }
  }, [sesionIniciada, navigate, usuario]);

  const handleCrearLiga = async () => {
    try {
      setLoading(true);
      setError(null);
      const token = localStorage.getItem("token");
      
      if (!token) {
        throw new Error("No estás autenticado");
      }

      const nombreLiga = usuario?.name ? `Liga de ${usuario.name}` : "Mi Liga";
      
      const response = await fetch("http://localhost:8086/api/leagues", {
        method: "POST",
        headers: {
          "Authorization": `Bearer ${token}`,
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify({
          name_league: nombreLiga,
          description: "Liga creada automáticamente"
        })
      });

      if (!response.ok) {
        const errorData = await response.json().catch(() => ({}));
        throw new Error(errorData.message || "Error al crear liga");
      }

      const result = await response.json();
      console.log("Liga creada:", result);
      setLiga(result.league);
      
    } catch (err) {
      console.error("Error al crear liga:", err);
      setError(err.message || "Error al crear la liga");
    } finally {
      setLoading(false);
    }
  };

  if (!sesionIniciada) return null;

  if (loading) {
    return (
      <div className="flex justify-center items-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#046942]"></div>
      </div>
    );
  }

  if (!liga) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[50vh]">
        <div className="bg-[#7bb369] p-8 rounded-lg shadow-lg text-center max-w-md w-full">
          <h2 className="text-2xl font-bold text-white mb-4">No tienes ninguna liga creada</h2>
          <p className="text-white mb-6">Crea tu liga para empezar a competir</p>
          
          {error && (
            <div className="text-red-300 mb-4 p-2 bg-white/10 rounded">
              {error}
            </div>
          )}
          
          <button
            onClick={handleCrearLiga}
            className="bg-[#046942] hover:bg-[#035336] text-white font-bold py-3 px-6 rounded-lg transition duration-300"
            disabled={loading}
          >
            {loading ? 'Creando Liga...' : 'Crear Mi Liga'}
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="flex flex-col items-center rounded-lg shadow-md w-7/8 mx-auto my-8 p-8 bg-[#7bb369] text-[#EEEEEE]">
      <header className="w-full max-w-3xl mb-8 text-center">
        <h1 className="text-[2rem] sm:text-[2.5rem] md:text-[3rem] font-extrabold tracking-wide text-white break-words">
          {liga.name_league.toUpperCase()}
        </h1>
        <p className="text-white mt-2">Administrador: {usuario?.name || "Tú"}</p>
        <hr className="mt-2 h-1 w-32 mx-auto bg-[#046942] border-0 rounded" />
      </header>

      <div className="w-full max-w-3xl bg-[#046942] rounded-lg shadow-lg p-6">
        <h2 className="text-xl font-bold text-[#7bb369] mb-4">Mi Equipo</h2>
        <p className="text-white">
          ¡Liga creada con éxito! Tienes 11 jugadores asignados.
        </p>
      </div>
    </div>
  );
};

export default Clasificacion;