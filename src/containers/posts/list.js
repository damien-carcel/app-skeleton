export default function listPosts() {
  // return fetch("https://api.example.com/items").then(response => response.json())

  const result = {};
  result.posts = [
    {
      'id': "post-1",
      'title': 'A first post',
      'content': 'A very uninteresting content.'
    },
    {
      'id': "post-2",
      'title': 'Another post',
      'content': 'Bla bla bla bla bla bla.'
    },
    {
      'id': "post-3",
      'title': 'And yet another',
      'content': 'Still nothing interesting.'
    }
  ];

  return new Promise((resolve) => {
    resolve(result);
  });
}
