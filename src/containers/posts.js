export function listPosts() {
  return fetch('http://localhost:8001/rest/blog/posts');
}

export function createPosts(data) {
  const headers = new Headers();
  headers.append("Accept", "application/json");
  headers.append("Content-Type", "application/json");

  fetch('http://localhost:8001/rest/blog/post/create', {
    body: JSON.stringify(data),
    headers: headers,
    method: 'POST'
  }).then(response => response.json());
}
