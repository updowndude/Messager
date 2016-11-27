import 'dart:html';
import 'package:bootjack/bootjack.dart';
import 'dart:convert';

void main() {
  Dropdown.use();
  Tab.use();
  Modal.use();
  Transition.use();

  checkElements();
}

void checkElements(){
  Element btnIndex = querySelector('.btn-group');
  Element aryGroupAdd = querySelector('#groupForm');
  Element elmToHome = querySelector('#toHome');
  Element elmFeedback = querySelector('#feedBack');
  ElementList aryInputLogin = querySelectorAll('#indexForm input');

  var groupsValues = null;

  if(aryGroupAdd != null) {
    HttpRequest.getString("../../json/groups.json").then((data) => groupsValues = JSON.decode(data));
    aryGroupAdd.children[1].children[1].onInput.listen((event) => inputGroup(aryGroupAdd, groupsValues));
    mainHelper(3, 1, 5, elmFeedback);
  } else if(btnIndex != null) {
   btnIndex.children.forEach((cur) =>
     cur.onClick.listen((event) => changeME(cur, btnIndex))
   );

   aryInputLogin.forEach((cur) =>
     cur.onInput.listen((event) => inputChanger(cur, aryInputLogin))
   );
  } else if(elmToHome != null) {
    elmToHome.onClick.listen((evnet) =>
      window.location.assign('groups.php')
    );
    mainHelper(1, 1, 4, querySelector('#postForm'));
  }
}

void mainHelper(int intMasterChildren, int intMasterWhere, int intBtnSumbit, var elmWhereIsMe) {
  elmWhereIsMe.children[intMasterChildren].children[intMasterWhere].onInput.listen((event) {
    elmWhereIsMe.children[intBtnSumbit].attributes.containsKey('disabled') == true ? elmWhereIsMe.children[intBtnSumbit].attributes.remove('disabled') : null;
    elmWhereIsMe.children[intMasterChildren].children[intMasterWhere].classes.contains('myError') == true ? elmWhereIsMe.children[intMasterChildren].children[intMasterWhere].classes.remove('myError') : null;
    if(elmWhereIsMe.children[intMasterChildren].children[intMasterWhere].value.trim() == '') {
      elmWhereIsMe.children[intMasterChildren].children[intMasterWhere].classes.add('myError');
      elmWhereIsMe.children[intBtnSumbit].setAttribute('disabled', 'false');
    }
  });
}

void inputGroup(var elmParen, var groupsValues) {
  var groupInput = elmParen.children[1].children[1];
  String strThere = '';

  for(int lcv=0;lcv<groupsValues.length;lcv++){
    if((strThere == '') || (strThere == null)) {
      strThere = groupInput.value.trim().toLowerCase() == groupsValues[lcv]['name'].toLowerCase() ? groupsValues[lcv]['name'] : '';
    }
  }
  groupInput.classes.contains('myError') == true ? groupInput.classes.remove('myError') : null;

  if((groupInput.value.trim() != '') && (strThere == '')) {
    elmParen.children[3].attributes.remove('disabled');
  } else {
    elmParen.children[3].setAttribute('disabled', 'false');
    groupInput.classes.add('myError');
  }
}

void inputChanger(var cur, ElementList aryIndexFormInputs) {
  String strTmp = '';
  int intCountSumbitTurnOn = 0;
  Element elmSumbit = querySelector('form button[type="submit"]');
  RegExp brithdayCheck = new RegExp(r'^[1-9]{1}\d{3}-\d{2}-\d{2}$');

  strTmp = cur.value.trim().length == 0 ? 'Must input something' : '';

  if(strTmp == '') {
    cur.classes.contains('myError') == true ? cur.classes.remove('myError') : null;
  } else {
    cur.classes.add('myError');
  }

  aryIndexFormInputs.forEach((places) => places.value != '' ? intCountSumbitTurnOn++ : null);

  aryIndexFormInputs.forEach((placeDate) {
    if ((placeDate.classes.contains('bDatePlace') == true) && (brithdayCheck.hasMatch(placeDate.value)) && (int.parse(placeDate.value.split('-')[1]) <= 12) && (int.parse(placeDate.value.split('-')[2]) <= 31)) {
      intCountSumbitTurnOn++;
    } else if ((document.activeElement.classes.contains('bDatePlace') == true) && (placeDate.classes.contains('bDatePlace'))) {
      placeDate.classes.add('myError');
    }
  });

  if (intCountSumbitTurnOn == 6) {
    elmSumbit.attributes.remove('disabled');
  } else if (elmSumbit.classes.contains('disabled') == false)  {
    elmSumbit.setAttribute('disabled', 'false');
  }
}

void changeME(Element elmCurrPlace, var eleParent) {
  String strActive = 'active';
  ElementList atriclesIndex = querySelectorAll('article');
  int intCountOfArticles = 0;

  eleParent.children.where((f) =>
    f.classes.contains(strActive)
  ).toList().forEach((elm) =>
    elm.classes.remove(strActive)
  );

  for (int lcv=0;lcv<eleParent.children.length;lcv++) {
    if (eleParent.children[lcv] == elmCurrPlace) {
      intCountOfArticles = lcv;
      break;
    }
  }

  atriclesIndex.forEach((f) => f.style.display = 'none');
  atriclesIndex[intCountOfArticles].style.display = 'block';

  elmCurrPlace.classes.add(strActive);
}
