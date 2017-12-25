export default function renderPost(post) {
  return `
    <div id="post-${post.id}" class="post">
      <div class="title">
        <h1>${post.title}</h1>
      </div>
      <div class="content">
        <p>${post.content}</p>
      </div>
    </div>
  `;
}
