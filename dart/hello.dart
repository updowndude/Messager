import 'dart:html';

void main() {
  String strButton = '.btn-group';
  Element btnIndex = querySelector(strButton);
  btnIndex.children.forEach((cur) =>
    cur.onClick.listen((event) => changeME(cur, btnIndex))
  );
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
