import '../assets/stylesheets/style.css';
import Background from '../assets/images/background.png';
import printMe from './print.js';

function component() {
  let background = new Image();
  let helloDiv = document.createElement('div');
  let btn = document.createElement('button');

  background.src = Background;

  btn.innerHTML = 'Click me and check the console!';
  btn.onclick = printMe;

  helloDiv.innerHTML = 'Hello world!';
  helloDiv.classList.add('hello');
  helloDiv.appendChild(background);
  helloDiv.appendChild(btn);

  return helloDiv;
}

document.body.appendChild(component());
