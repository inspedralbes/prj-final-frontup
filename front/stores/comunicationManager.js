  import { reactive } from 'vue';

  const baseURL = 'http://127.0.0.1:8000/api';

  const useCommunicationManager = () => {
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
        const response = await fetch(`${baseURL}${url}`, options);
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
    const guardarProyectoDB = async (proyecto) => {
      fetch('http://localhost:8000/api/projects',{
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(proyecto),
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la solicitud: ' + response.status);
        }
        return response.json();
      })
      .then(data => {
        console.log('Respuesta del servidor:', data);
      })
      .catch(error => {
        console.error('Hubo un problema con el fetch:', error);
      });
    }
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

    return {
      state,
      loginUser,
      registerUser,
      logoutUser,
      guardarProyectoDB,
    };
  };

  export default useCommunicationManager;
