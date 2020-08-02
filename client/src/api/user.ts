export interface UserData {
  id: string;
  email: string;
  firstName: string;
  lastName: string;
}

function getApiUrl(route: string, userId?: string): string {
  const baseUrl = process.env.API_BASE_URL;

  if (userId) {
    return baseUrl + route.replace("{id}", userId);
  }

  return baseUrl + route;
}

function createHeaders(): Headers {
  const headers = new Headers();
  headers.append("Accept", "application/ld+json");

  return headers;
}

function createPostHeaders(): Headers {
  const headers = createHeaders();
  headers.append("Content-Type", "application/ld+json");

  return headers;
}

export function getUserCollection(page: number, limit: number): Promise<UserData> {
  const url = getApiUrl("/api/users") + `?_page=${page}&_limit=${limit}`;

  return fetch(url, {
    headers: createHeaders(),
    method: "GET",
    mode: "cors",
  }).then((response) => response.json());
}

export function getUser(userId: string): Promise<UserData> {
  const url = getApiUrl("/api/users/{id}", userId);

  return fetch(url, {
    headers: createHeaders(),
    method: "GET",
    mode: "cors",
  }).then((response) => response.json());
}

export function createUser(data: UserData): Promise<Response> {
  const url = getApiUrl("/api/users");

  return fetch(url, {
    body: JSON.stringify({
      email: data.email,
      firstName: data.firstName,
      lastName: data.lastName,
    }),
    headers: createPostHeaders(),
    method: "POST",
    mode: "cors",
  });
}

export function updateUser(userId: string, data: UserData): Promise<Response> {
  const url = getApiUrl("/api/users/{id}", userId);

  return fetch(url, {
    body: JSON.stringify(data),
    headers: createPostHeaders(),
    method: "PUT",
    mode: "cors",
  });
}

export function deleteUser(userId: string): Promise<Response> {
  const url = getApiUrl("/api/users/{id}", userId);

  return fetch(url, {
    headers: createPostHeaders(),
    method: "DELETE",
    mode: "cors",
  });
}
