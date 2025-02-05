import { reactive } from 'vue';


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
  const guardarProyectoDB = async (proyecto) => {
    fetch(`${laravelURL}/projects`, {
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
  const chatIA = async (mensaje, html, css, js) => {
    try {
      const response = await fetch(`${nodeURLZ}/pregunta`, {
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

  return {
    state,
    loginUser,
    registerUser,
    logoutUser,
    guardarProyectoDB,
    chatIA,
  };
};

export default useCommunicationManager;
