export interface BlogPostData {
  id: string;
  title: string;
  content: string;
}

export function listPosts() {
  const url = getApiUrl('/posts');

  return fetch(url, {
    headers: createHeaders(),
    method: 'GET',
  }).then((response) => response.json());
}

export function getPost(postId: string) {
  const url = getApiUrl('/posts/{id}', postId);

  return fetch(url, {
    headers: createHeaders(),
    method: 'GET',
  }).then((response) => response.json());
}

export function createPost(data: BlogPostData) {
  const url = getApiUrl('/posts');

  return fetch(url, {
    body: JSON.stringify(data),
    headers: createHeaders(),
    method: 'POST',
  }).then((response) => response.json());
}

export function updatePost(postId: string, data: BlogPostData) {
  const url = getApiUrl('/posts/{id}', postId);

  return fetch(url, {
    body: JSON.stringify(data),
    headers: createHeaders(),
    method: 'PATCH',
  }).then((response) => response.json());
}

export function deletePost(postId: string) {
  const url = getApiUrl('/posts/{id}', postId);

  return fetch(url, {
    headers: createHeaders(),
    method: 'DELETE',
  }).then((response) => response.json());
}

function getApiUrl(route: string, postId?: string) {
  const baseUrl = process.env.API_BASE_URL;

  if (postId) {
    return baseUrl + route.replace('{id}', postId);
  }

  return baseUrl + route;
}

function createHeaders() {
  const headers = new Headers();
  headers.append('Accept', 'application/json');
  headers.append('Content-Type', 'application/json');

  return headers;
}
