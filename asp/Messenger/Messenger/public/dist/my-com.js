/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports) {

	'use strict';

	function main() {
	  if (document.title == 'Home') {
	    formChecker('#loginForm', '#body_btnLogin');
	  } else if (document.title == 'Groups') {
	    formChecker('#addGroupForm', '#body_btnAddGroup');
	    formChecker('#feedBackForm', '#body_btnFeedbackSumbit');
	    formChecker('#adimLogin', '#body_btnAdimLogin');

	    if (document.querySelector('#body_dListFeedback') != null) {
	      var dlistFeedback = document.querySelector('#body_dListFeedback');

	      dlistFeedback.innerHTML = '\n      <thead>\n             <tr>\n               <th>Name</th>\n               <th>Birthday</th>\n               <th>Message</th>\n               <th>Rating</th>\n               <th>Group Name</th>\n               <th>Posted</th>\n            </tr>\n        </thead>\n      ' + dlistFeedback.innerHTML;
	    }

	    if (document.querySelector('#Groups').innerHTML.trim().length == 0) {
	      document.querySelector('#About').style.paddingTop = '5rem';
	    }
	  } else if (document.title == 'Posts') {
	    formChecker('#postAdder', '#body_btnNewPost');
	    if (document.querySelector('#Posts').innerHTML.trim().length == 0) {
	      document.querySelector('#Add').style.paddingTop = '4rem';
	    }
	  }
	}

	function formChecker(strForm, strBtnSumbit) {
	  if (document.querySelectorAll(strForm + ' .message').length == 0) {
	    formCheckerHelper(strForm, strBtnSumbit, strForm + ' input[type="text"]');
	  } else {
	    formCheckerHelper(strForm, strBtnSumbit, strForm + ' input[type="text"],  ' + strForm + ' .message');
	  }
	}

	function formCheckerHelper(strForm, strBtnSumbit, strTopLevel) {
	  document.querySelectorAll(strTopLevel).forEach(function (cur) {
	    cur.addEventListener('input', function () {
	      var intCorrect = 0;

	      if (cur.value.trim() === '') {
	        cur.classList.add('myError');
	        document.querySelector('' + strBtnSumbit).setAttribute('disabled', 'true');
	      } else {
	        cur.classList.contains('myError') == true ? cur.classList.remove('myError') : null;
	        document.querySelectorAll(strTopLevel).forEach(function (cur, place, array) {
	          var strValue = cur.value.trim();

	          if (cur.classList.contains('bDay') == false && cur.classList.contains('message') == false && cur.value.trim() != '') {
	            intCorrect++;
	          } else if (cur.classList.contains('bDay') == true && /^[1-9]{1}\d{3}-\d{2}-\d{2}$/.test(strValue) == true && strValue.split('-')[1] <= 12 && strValue.split('-')[2] <= 31) {
	            intCorrect++;
	          } else if (cur.classList.contains('message') == true && cur.value.trim() != '') {
	            intCorrect++;
	          } else {
	            intCorrect--;
	          }

	          intCorrect === array.length ? document.querySelector('' + strBtnSumbit).removeAttribute('disabled') : document.querySelector('' + strBtnSumbit).setAttribute('disabled', 'true');
	        });
	      }
	    });
	  });
	}

	main();

/***/ }
/******/ ]);