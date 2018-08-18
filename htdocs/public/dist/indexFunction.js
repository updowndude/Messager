const { fromEvent } = rxjs;
const { map, debounceTime} = rxjs.operators;

export function indexFunction() {
  fromEvent(document.getElementsByName('helloDartBtn'), 'click')
    .pipe(
      map(val => val.target.id),
      map(val => val[val.length - 1]),
      map(val => val - 1))
    .subscribe(val => {
      document.querySelector('.panel-body').childNodes.forEach((element, index) => {
        if(index <= 1) {
          element.style.display = 'none';
        }       
      });
      document.querySelector('.panel-body').children[val].style.display = 'block';
      document.querySelector('body > main > div > div > div.panel-body > div > div').childNodes.forEach(element => {
        element.classList.contains('active') == true ? element.classList.remove('active') : element.classList.add('active');
      });
    });
    fromEvent(document.getElementsByName('bDate')[0], 'input')
      .pipe(
        debounceTime(1000),
        map(val => document.getElementsByName('bDate')[0].value),
        map(val => val.match(/^[1-9]{1}\d{3}-\d{2}-\d{2}$/g) != null ? true : false)
      )
      .subscribe(val => {
        document.getElementsByName('bDate')[0].classList.contains('myError') ? document.getElementsByName('bDate')[0].classList.remove('myError') : null;
        val == false ? document.getElementsByName('bDate')[0].classList.add('myError') : null;
     });
     //disabled=""
}