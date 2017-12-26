import '../assets/stylesheets/style.less';

import React from 'react';
import ReactDOM from 'react-dom';

import ListPosts from './components/ListPosts';

fetch('/rest/blog_post/list')
  .then((response) => response.json())
  .then((posts) => {
    ReactDOM.render(
      <ListPosts posts={posts}/>,
      document.getElementById('root')
    );
  }
);
