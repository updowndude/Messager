function main() {
  if(document.title == 'Home') {
    formChecker('#loginForm', 'body_btnLogin');
  } else if(document.title == 'Groups') {
    formChecker('#addGroupForm', 'body_btnAddGroup');
  }
}

function formChecker(strForm, strBtnSumbit) {
  document.querySelectorAll(`${strForm} input[type="text"] `)
  .forEach((cur) => {
    cur.addEventListener('input', () => {
      let intCorrect = 0;

      if (cur.value.trim() === '') {
        cur.classList.add('myError');
        document.querySelector(`#${strBtnSumbit}`).setAttribute('disabled','true')
      } else {
        cur.classList.contains('myError') == true ? cur.classList.remove('myError') : null;
        document.querySelectorAll(`${strForm} input[type="text"] `)
        .forEach((cur, place, array) =>  {
          if((cur.classList.contains('bDay') == false) && (cur.value.trim() != '')) {
            intCorrect++;
          } else if((cur.classList.contains('bDay') ==  true) && (/^\d{2}\/\d{2}\/\d{4}$/.test(cur.value.trim()) == true)) {
            intCorrect++;
          } else {
            intCorrect--;
          }
          intCorrect === array.length ?  document.querySelector(`#${strBtnSumbit}`).removeAttribute('disabled') : null;
        })
      }
    })
  });
}

main();
