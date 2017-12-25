import '../assets/stylesheets/style.less';

import React from 'react';
import ReactDOM from 'react-dom';

import ListPosts from './components/ListPosts';

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

ReactDOM.render(
  <ListPosts posts={posts}/>,
  document.getElementById('root')
);
