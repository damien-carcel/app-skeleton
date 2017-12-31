export function listPosts() {
  const url = 'http://localhost:8001/rest/blog/posts';

  return fetch(url).then(response => response.json());
}

export function getPost(postId) {
  const url = 'http://localhost:8001/rest/blog/post/' + postId;

  return fetch(url, {
    headers: createHeaders(),
    method: 'GET'
  }).then(response => response.json());
}

export function deletePost(postId) {
  const url = 'http://localhost:8001/rest/blog/post/' + postId + '/delete';

  return fetch(url, {
    headers: createHeaders(),
    method: 'DELETE'
  }).then(response => response.json());
}

export function createPost(data) {
  const url = 'http://localhost:8001/rest/blog/post/create';

  return fetch(url, {
    body: JSON.stringify(data),
    headers: createHeaders(),
    method: 'POST'
  }).then(response => response.json());
}

export function updatePost(postId, data) {
  const url = 'http://localhost:8001/rest/blog/post/' + postId + '/update';

  return fetch(url, {
    body: JSON.stringify(data),
    headers: createHeaders(),
    method: 'POST'
  }).then(response => response.json());
}

function createHeaders() {
  const headers = new Headers();
  headers.append("Accept", "application/json");
  headers.append("Content-Type", "application/json");

  return headers;
}
