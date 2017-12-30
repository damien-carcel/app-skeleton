export default function listPosts() {
  return fetch('http://localhost:8001/rest/blog/posts');
}
