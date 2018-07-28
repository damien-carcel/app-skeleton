import config from '../../config/api.json';

export function listPosts() {
  const route = config.api_config.routes.list;
  const url = getApiUrl(route);

  return fetch(url, {
    headers: createHeaders(),
    method: 'GET'
  }).then(response => response.json());
}

export function getPost(postId) {
  const route = config.api_config.routes.get;
  const url = getApiUrl(route, postId);

  return fetch(url, {
    headers: createHeaders(),
    method: 'GET'
  }).then(response => response.json());
}

export function deletePost(postId) {
  const route = config.api_config.routes.delete;
  const url = getApiUrl(route, postId);

  return fetch(url, {
    headers: createHeaders(),
    method: 'DELETE'
  }).then(response => response.json());
}

export function createPost(data) {
  const route = config.api_config.routes.create;
  const url = getApiUrl(route);

  return fetch(url, {
    body: JSON.stringify(data),
    headers: createHeaders(),
    method: 'POST'
  }).then(response => response.json());
}

export function updatePost(postId, data) {
  const route = config.api_config.routes.update;
  const url = getApiUrl(route, postId);

  return fetch(url, {
    body: JSON.stringify(data),
    headers: createHeaders(),
    method: 'PATCH'
  }).then(response => response.json());
}

function getApiUrl(route, postId = null) {
  const protocol = config.api_config.protocol;
  const host = config.api_config.host;
  const port = config.api_config.port;
  const prefix = config.api_config.prefix;

  const baseUrl = protocol + '://' + host + ':' + port + prefix;

  if (null !== postId) {
    return baseUrl + route.replace('{id}', postId);
  }

  return baseUrl + route;
}

function createHeaders() {
  const headers = new Headers();
  headers.append("Accept", "application/json");
  headers.append("Content-Type", "application/json");

  return headers;
}
