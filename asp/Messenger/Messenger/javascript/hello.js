function main() {
  if(document.title == 'Home') {
    formChecker('#loginForm', '#body_btnLogin');
  } else if(document.title == 'Groups') {
    formChecker('#addGroupForm', '#body_btnAddGroup');
    formChecker('#feedBackForm', '#body_btnFeedbackSumbit');
  }
}

function formChecker(strForm, strBtnSumbit) {
  if (document.querySelectorAll(`${strForm} .message`).length == 0) {
    formCheckerHelper(strForm, strBtnSumbit, `${strForm} input[type="text"]`);
  } else {
    formCheckerHelper(strForm, strBtnSumbit, `${strForm} input[type="text"],  ${strForm} .message`);
  }
}

function formCheckerHelper(strForm, strBtnSumbit, strTopLevel) {
  document.querySelectorAll(strTopLevel)
  .forEach((cur) => {
    cur.addEventListener('input', () => {
      let intCorrect = 0;

      if (cur.value.trim() === '') {
        cur.classList.add('myError');
        document.querySelector(`${strBtnSumbit}`).setAttribute('disabled','true')
      } else {
        cur.classList.contains('myError') == true ? cur.classList.remove('myError') : null;
        document.querySelectorAll(strTopLevel)
        .forEach((cur, place, array) =>  {
          let strValue = cur.value.trim();

          if((cur.classList.contains('bDay') == false) && (cur.classList.contains('message') == false) && (cur.value.trim() != '')) {
            intCorrect++;
          } else if((cur.classList.contains('bDay') ==  true) && (/^[1-9]{1}\d{3}-\d{2}-\d{2}$/.test(strValue) == true) && (strValue.split('-')[1] <= 12) && (strValue.split('-')[2] <= 31)) {
            intCorrect++;
          } else if ((cur.classList.contains('message') ==  true) && (cur.value.trim() != '')) {
            intCorrect++;
          } else {
            intCorrect--;
          }

          intCorrect === array.length ?  document.querySelector(`${strBtnSumbit}`).removeAttribute('disabled') : document.querySelector(`${strBtnSumbit}`).setAttribute('disabled','true');
        })
      }
    })
  });
}

main();
