import '../assets/stylesheets/style.less';
import listPosts from '../src/components/ListPosts';

const posts = [
  {
    'id': 1,
    'title': 'A first post',
    'content': 'A very uninteresting content.'
  },
  {
    'id': 2,
    'title': 'Another post',
    'content': 'Bla bla bla bla bla bla.'
  },
  {
    'id': 3,
    'title': 'And yet another',
    'content': 'Still nothing interesting.'
  }
];

const renderedPosts = document.createElement('div');
renderedPosts.innerHTML = listPosts(posts);

const root = document.getElementById('root');
root.addClass('container');
root.appendChild(renderedPosts);
