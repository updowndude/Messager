import { indexFunction } from './indexFunction.js';

function Main() {
  if (document.title == 'Home') {
    document.querySelector('body > main > div > div > div.panel-body > article:nth-child(1)').style.display = 'none';
    document.querySelector('#helloDartBtn2').classList.add('active');
    indexFunction();
  }
}

Main();