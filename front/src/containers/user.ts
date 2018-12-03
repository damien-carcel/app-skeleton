export interface UserData {
  id: string;
  username: string;
  firstName: string;
  lastName: string;
}

export function listUsers() {
  const url = getApiUrl('/users');

  return fetch(url, {
    headers: createHeaders(),
    method: 'GET',
  }).then((response) => response.json());
}

export function getUser(userId: string) {
  const url = getApiUrl('/users/{id}', userId);

  return fetch(url, {
    headers: createHeaders(),
    method: 'GET',
  }).then((response) => response.json());
}

export function createUser(data: UserData) {
  const url = getApiUrl('/users');

  return fetch(url, {
    body: JSON.stringify(data),
    headers: createHeaders(),
    method: 'POST',
  }).then((response) => response.json());
}

export function updateUser(userId: string, data: UserData) {
  const url = getApiUrl('/users/{id}', userId);

  return fetch(url, {
    body: JSON.stringify(data),
    headers: createHeaders(),
    method: 'PATCH',
  }).then((response) => response.json());
}

export function deleteUser(userId: string) {
  const url = getApiUrl('/users/{id}', userId);

  return fetch(url, {
    headers: createHeaders(),
    method: 'DELETE',
  }).then((response) => response.json());
}

function getApiUrl(route: string, userId?: string) {
  const baseUrl = process.env.API_BASE_URL;

  if (userId) {
    return baseUrl + route.replace('{id}', userId);
  }

  return baseUrl + route;
}

function createHeaders() {
  const headers = new Headers();
  headers.append('Accept', 'application/json');
  headers.append('Content-Type', 'application/json');

  return headers;
}
