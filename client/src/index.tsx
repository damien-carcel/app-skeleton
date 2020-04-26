import React from 'react';
import ReactDOM from 'react-dom';
import Modal from 'react-modal';
import '../assets/stylesheets/style.less';
import UserCollection from './components/UserCollection';

Modal.setAppElement('#app');
ReactDOM.render(
  <UserCollection/>,
  document.getElementById('app'),
);
