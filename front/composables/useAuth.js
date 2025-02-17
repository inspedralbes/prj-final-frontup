import { useFetch } from '#app';

export default function useAuth() {
  const loginUser = async (data) => {
    const { data: response, error } = await useFetch('/api/login', { method: 'POST', body: data });
    if (!error.value) {
      localStorage.setItem('token', response.value.token);
      return true;
    }
    return false;
  };    

  const registerUser = async (data) => {
    const { data: response, error } = await useFetch('/api/register', { method: 'POST', body: data });
    if (!error.value) {
      localStorage.setItem('token', response.value.token);
      return true;
    }
    return false;
  };

  return { loginUser, registerUser };
}
