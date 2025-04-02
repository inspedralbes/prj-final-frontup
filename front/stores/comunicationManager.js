import { reactive } from 'vue';
import { defineStore } from 'pinia';

const useCommunicationManager = () => {
  const config = useRuntimeConfig(); 
  const laravelURL = config.public.apiLaravelUrl;
  const nodeURL = config.public.nodeUrl;

  const state = reactive({
    loading: false,
    error: null,
    token: localStorage.getItem('auth_token'),
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

  const loginUser = async (credentials) => {
    try {
      const response = await request('/login', 'POST', credentials);
      if (response.token) {
        localStorage.setItem('auth_token', response.token);
        state.token = response.token;
      }
      return true;
    } catch (error) {
      return false;
    }
  };

  const registerUser = async (userData) => {
    try {
      const response = await request('/register', 'POST', userData);
      if (response.token) {
        localStorage.setItem('auth_token', response.token);
        state.token = response.token;
      }
      return true;
    } catch (error) {
      return false;
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
    console.log('id pasado', id);
    console.log('proyecto pasado', proyecto);

    try {
      const response = await fetch(`${laravelURL}/projects/${id}`, {
        method: 'PUT',
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

  const borrarProyectoDB = async (id) => {
    console.log('id pasado', id);

    try {
      const response = await fetch(`${laravelURL}/projects/${id}`, {
        method: 'DELETE',
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
      const response = await request('/likes', 'POST', { project_id: projectId });
      return true;
    } catch (error) {
      return false;
    }
  };
  
  const removeLike = async (projectId) => {
    try {
      await request(`/likes/${projectId}`, 'DELETE');
      return true;
    } catch (error) {
      return false;
    }
  };
  
  const checkLike = async (projectId) => {
    try {
      const response = await request(`/likes/check/${projectId}`, 'GET');
      return response.hasLiked || false;
    } catch (error) {
      return false;
    }
  };
  
  const getLikeCount = async (projectId) => {
    try {
      const response = await request(`/likes/count/${projectId}`, 'GET');
      return response.count || 0;
    } catch (error) {
      return 0;
    }
  };
  
  const toggleLike = async (projectId) => {
    try {
      const hasLiked = await checkLike(projectId);
      if (hasLiked) {
        await removeLike(projectId);
      } else {
        await addLike(projectId);
      }
      return true;
    } catch (error) {
      return false;
    }
  };
  
  const getUserLikes = async () => {
    try {
      const response = await request('/likes/user', 'GET');
      return response || [];
    } catch (error) {
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

  return {
    state,
    loginUser,
    registerUser,
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
    getUserLikes
  };
};

export default useCommunicationManager;
