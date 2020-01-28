import React from 'react';
import ReactDOM from 'react-dom';
import Modal from 'react-modal';
import '../assets/stylesheets/style.less';
import UserCollection from './components/UserCollection';

/**
 * Creates a "noscript" element and adds it to the body.
 * This will display a warning if the user disabled JavaScript in its browser.
 */
const addNoScriptElement = () => {
  const noScript = document.createElement('noscript');
  const noScriptDiv = document.createElement('div');
  const noScriptContent = document.createTextNode(`
JavaScript is not activated in your web browser.
<br>
Please activate it in order to see the web site.
`);

  noScriptDiv.appendChild(noScriptContent);
  noScript.appendChild(noScriptDiv);

  document.body.appendChild(noScript);
};

/**
 * Created and add the "root" div to the body.
 * This is the container of the application.
 */
const addRootDiv = () => {
  const rootDiv = document.createElement('div');
  rootDiv.setAttribute('id', 'root');
  document.body.appendChild(rootDiv);
};

addNoScriptElement();
addRootDiv();

Modal.setAppElement('#root');
ReactDOM.render(
  <UserCollection/>,
  document.getElementById('root'),
);
