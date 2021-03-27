export interface UserData {
  id: string;
  email: string;
  firstName: string;
  lastName: string;
}

const getApiUrl = (route: string, userId?: string): string => {
  const baseUrl = process.env.API_BASE_URL;

  if (userId) {
    return baseUrl + route.replace('{id}', userId);
  }

  return baseUrl + route;
};

const createHeaders = (): Headers => {
  const headers = new Headers();
  headers.append('Accept', 'application/json');

  return headers;
};

const getUserCollection = (page: number, limit: number): Promise<Response> => {
  const url = getApiUrl('/api/users') + `?_page=${page}&_limit=${limit}`;
  const headers = createHeaders();

  return fetch(url, {
    headers: headers,
    method: 'GET',
    mode: 'cors',
  });
};

const getUser = (userId: string): Promise<Response> => {
  const url = getApiUrl('/api/users/{id}', userId);
  const headers = createHeaders();

  return fetch(url, {
    headers: headers,
    method: 'GET',
    mode: 'cors',
  });
};

const createUser = (data: UserData): Promise<Response> => {
  const url = getApiUrl('/api/users');
  const headers = createHeaders();
  headers.append('Content-Type', 'application/json');

  return fetch(url, {
    body: JSON.stringify({
      email: data.email,
      firstName: data.firstName,
      lastName: data.lastName,
    }),
    headers: headers,
    method: 'POST',
    mode: 'cors',
  });
};

const updateUser = (userId: string, data: UserData): Promise<Response> => {
  const url = getApiUrl('/api/users/{id}', userId);
  const headers = createHeaders();
  headers.append('Content-Type', 'application/json');

  return fetch(url, {
    body: JSON.stringify(data),
    headers: headers,
    method: 'PUT',
    mode: 'cors',
  });
};

const deleteUser = (userId: string): Promise<Response> => {
  const url = getApiUrl('/api/users/{id}', userId);
  const headers = createHeaders();
  headers.append('Content-Type', 'application/json');

  return fetch(url, {
    headers: headers,
    method: 'DELETE',
    mode: 'cors',
  });
};

export { getUserCollection, getUser, createUser, updateUser, deleteUser };
