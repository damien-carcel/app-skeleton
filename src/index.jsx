import '../assets/stylesheets/style.less';
import ListPosts from './components/ListPosts';
import Modal from 'react-modal';
import React from 'react';
import ReactDOM from 'react-dom';

Modal.setAppElement('#root');

ReactDOM.render(
  <ListPosts/>,
  document.getElementById('root')
);
