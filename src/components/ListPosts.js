import createButton from './Create';
import renderPost from './Post';

export default function listPosts(posts) {
  const button = createButton();
  const renderedPosts = posts.map((post) => renderPost(post));

  return `
    <div>${button}</div>
    <div class="blog-posts">${renderedPosts}</div>
  `;
}
