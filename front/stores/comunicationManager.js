import { reactive, ref } from 'vue';
import { defineStore } from 'pinia';
import { io } from 'socket.io-client';

const useCommunicationManager = () => {
  const config = useRuntimeConfig();
  const laravelURL = config.public.apiLaravelUrl;
  const nodeURL = config.public.nodeUrl;

  const socket = ref(false);

  const connect = () => {
    if (!socket.value && !socket.value) {
      socket.value = io(nodeURL, {
        transports: ['websocket'],
        withCredentials: true,
      });

      socket.value.on('connect', () => {
        console.log('Conectado al servidor WebSocket');
      });

      socket.value.on('disconnect', () => {
        console.log('Desconectado del servidor WebSocket');
        socket.value = false; // Indicar que la conexión s'ha perdut
      });
    }
  };

  const disconnect = () => {
    if (socket.value) {
      socket.value.disconnect();
      socket.value = null;
    }
  };

  const joinRoom = (roomId) => {
    if (socket.value) {
      socket.value.emit('joinRoom', roomId);
    }
  };

  const state = reactive({
    loading: false,
    error: null,
    token: localStorage.getItem('token'),
  });

  const request = async (url, method = 'GET', data = null) => {
    state.loading = true;
    state.error = null;

    const headers = {
      'Content-Type': 'application/json',
      'Authorization': state.token ? `Bearer ${state.token}` : '',
    };

    const options = {
      method,
      headers,
    };

    if (data) {
      options.body = JSON.stringify(data);
    }

    try {
      const response = await fetch(`${laravelURL}${url}`, options);
      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message || 'Error desconocido');
      }

      return result;
    } catch (err) {
      state.error = err.message;
      throw err;
    } finally {
      state.loading = false;
    }
  };

  const crearProyectoDB = async (proyecto) => {
    try {
      const response = await fetch(`${laravelURL}/projects`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(proyecto),
      });

      if (!response.ok) {
        throw new Error('Error en la solicitud: ' + response.status);
      }

      const data = await response.json();
      console.log('Respuesta del servidor:', data);
      return data;
    } catch (error) {
      console.error('Hubo un problema con el fetch:', error);
      throw error;
    }
  };

  const guardarProyectoDB = async (proyecto, id) => {

    try {
      const token = localStorage.getItem('token');
      const response = await fetch(`${laravelURL}/projects/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify(proyecto),
      });
      console.log('token que se manda', token);
      if (!response.ok) {
        throw new Error('Error en la solicitud: ' + response.status);
      }

      const data = await response.json();
      console.log('Respuesta del servidor:', data);
      return data;
    } catch (error) {
      console.error('Hubo un problema con el fetch:', error);
      throw error;
    }
  };

  const borrarProyectoDB = async (id) => {
    try {
      const token = localStorage.getItem('token');
      const response = await fetch(`${laravelURL}/projects/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      });

      if (!response.ok) {
        throw new Error('Error en la solicitud: ' + response.status);
      }

      const data = await response.json();
      console.log('Respuesta del servidor:', data);
      return data;
    } catch (error) {
      console.error('Hubo un problema con el fetch:', error);
      throw error;
    }
  };

  const addLike = async (projectId) => {
    try {
      const token = localStorage.getItem('token');
      if (!token) return false;

      const response = await fetch(`${laravelURL}/likes`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ project_id: projectId })
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      return true;
    } catch (error) {
      console.error('Error adding like:', error);
      return false;
    }
  };

  const removeLike = async (projectId) => {
    try {
      const token = localStorage.getItem('token');
      if (!token) return false;

      const response = await fetch(`${laravelURL}/likes/${projectId}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      return true;
    } catch (error) {
      console.error('Error removing like:', error);
      return false;
    }
  };

  const checkLike = async (projectId) => {
    try {
      const token = localStorage.getItem('token');
      if (!token) return false;

      const response = await fetch(`${laravelURL}/likes/check/${projectId}`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      return data.hasLiked || false;
    } catch (error) {
      console.error('Error checking like status:', error);
      return false;
    }
  };

  const getLikeCount = async (projectId) => {
    try {
      const response = await fetch(`${laravelURL}/likes/count/${projectId}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      return data.count || 0;
    } catch (error) {
      console.error('Error getting like count:', error);
      return 0;
    }
  };

  const toggleLike = async (projectId) => {
    try {
      const token = localStorage.getItem('token');
      if (!token) return false;

      const hasLiked = await checkLike(projectId);
      if (hasLiked) {
        await removeLike(projectId);
      } else {
        await addLike(projectId);
      }
      return true;
    } catch (error) {
      console.error('Error toggling like:', error);
      return false;
    }
  };

  const getUserLikes = async () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) return [];

      const response = await fetch(`${laravelURL}/likes/user`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      return data || [];
    } catch (error) {
      console.error('Error getting user likes:', error);
      return [];
    }
  };

  const getUserAllLikes = async () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) return [];

      const response = await fetch(`${laravelURL}/likes/user`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      return data || [];
    } catch (error) {
      console.error('Error getting user likes:', error);
      return [];
    }
  };

  const chatIA = async (mensaje, html, css, js) => {
    try {
      const response = await fetch(`${nodeURL}/pregunta`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          pregunta: mensaje,
          html: html,
          css: css,
          js: js,
        }),
      });

      if (!response.ok) {
        throw new Error('Error en la solicitud: ' + response.status);
      }

      const data = await response.text();
      return data;
    } catch (error) {
      console.error('Error:', error);
      throw error;
    }
  };

  const logoutUser = async () => {
    try {
      await request('/logout', 'POST');
      localStorage.removeItem('auth_token');
      state.token = null;
      return true;
    } catch (error) {
      return false;
    }
  };

  const useProyectoStore = defineStore('proyecto', {
    state: () => ({
      proyecto: null,
    }),
    actions: {
      async obtenerProyecto(id) {
        try {
          const response = await fetch(`${laravelURL}/projects/${id}`);
          if (!response.ok) {
            throw new Error('Error en la solicitud');
          }
          const data = await response.json();
          this.proyecto = data;
          return data;
        } catch (error) {
          console.error('Error al obtener el proyecto:', error);
          return null;
        }
      },
    },
  });

  const loginUser = async (email, password) => {
    try {
      const response = await fetch("http://127.0.0.1:8000/api/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || "Credenciales inválidas");
      }

      return {
        success: true,
        data,
      };
    } catch (error) {
      return {
        success: false,
        message: error.message || "Error de red. No se pudo conectar al servidor.",
      };
    }
  };

  const registerUser = async (formData) => {
    const avatarUrl = `https://api.dicebear.com/9.x/personas/svg?seed=${formData.name}`;
  
    try {
      const response = await fetch("http://localhost:8000/api/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({
          ...formData,
          avatar: avatarUrl,
        }),
      });
  
      const data = await response.json();
      if (!response.ok) {
        throw new Error(data.message || "Error al crear la cuenta");
      }
  
      const loginResponse = await fetch("http://127.0.0.1:8000/api/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          email: formData.email,
          password: formData.password,
        }),
      });
  
      const loginData = await loginResponse.json();
      if (!loginResponse.ok) {
        throw new Error(loginData.message || "Error al iniciar sesión automáticamente");
      }
  
      return {
        success: true,
        user: loginData.user,
        token: loginData.token,
        avatar: avatarUrl,
      };
    } catch (error) {
      return {
        success: false,
        message: error.message || "Error de red. No se pudo conectar al servidor.",
      };
    }
  };

  const fetchProjects = async ({ page = 1, searchQuery = "", sortCriteria = "default" }) => {
    try {
      const url = new URL("http://localhost:8000/api/projects");
      url.searchParams.append("page", page);
      if (searchQuery) {
        url.searchParams.append("search", searchQuery);
      }
      if (sortCriteria && sortCriteria !== "default") {
        url.searchParams.append("sort", sortCriteria);
      }
  
      const token = localStorage.getItem("token");
      if (!token) throw new Error("No hay token guardado");
  
      const response = await fetch(url, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      });
  
      const data = await response.json();
  
      if (!response.ok) {
        throw new Error(data.message || "Error cargando proyectos");
      }
  
      return {
        success: true,
        data,
      };
    } catch (error) {
      return {
        success: false,
        message: error.message,
      };
    }
  };

  const fetchAllProjects = async ({ page = 1, searchQuery = "", sortCriteria = "default" }) => {
    try {
      const params = new URLSearchParams();
      params.append("page", page);
      if (searchQuery) params.append("search", searchQuery);
      if (sortCriteria && sortCriteria !== "default") params.append("sort", sortCriteria);
  
      const response = await fetch(`http://localhost:8000/api/projects/all?${params.toString()}`, {
        headers: {
          "Content-Type": "application/json",
        },
      });
  
      const data = await response.json();
  
      if (!response.ok) {
        throw new Error(data.message || "Error cargando proyectos");
      }
  
      return {
        success: true,
        data,
      };
    } catch (error) {
      return {
        success: false,
        message: error.message,
      };
    }
  };  
  

  return {
    state,
    logoutUser,
    guardarProyectoDB,
    chatIA,
    crearProyectoDB,
    borrarProyectoDB,
    useProyectoStore,
    addLike,
    removeLike,
    checkLike,
    getLikeCount,
    toggleLike,
    getUserLikes,
    getUserAllLikes,
    connect,
    disconnect,
    joinRoom,
    socket,
    loginUser,
    registerUser,
    fetchProjects,
    fetchAllProjects,
  };
};

export default useCommunicationManager;
