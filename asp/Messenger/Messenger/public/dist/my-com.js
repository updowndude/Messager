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
	    formChecker('#loginForm', 'body_btnLogin');
	  } else if (document.title == 'Groups') {
	    formChecker('#addGroupForm', 'body_btnAddGroup');
	  }
	}

	function formChecker(strForm, strBtnSumbit) {
	  document.querySelectorAll(strForm + ' input[type="text"] ').forEach(function (cur) {
	    cur.addEventListener('input', function () {
	      var intCorrect = 0;

	      if (cur.value.trim() === '') {
	        cur.classList.add('myError');
	        document.querySelector('#' + strBtnSumbit).setAttribute('disabled', 'true');
	      } else {
	        cur.classList.contains('myError') == true ? cur.classList.remove('myError') : null;
	        document.querySelectorAll(strForm + ' input[type="text"] ').forEach(function (cur, place, array) {
	          if (cur.classList.contains('bDay') == false && cur.value.trim() != '') {
	            intCorrect++;
	          } else if (cur.classList.contains('bDay') == true && /^\d{2}\/\d{2}\/\d{4}$/.test(cur.value.trim()) == true) {
	            intCorrect++;
	          } else {
	            intCorrect--;
	          }
	          intCorrect === array.length ? document.querySelector('#' + strBtnSumbit).removeAttribute('disabled') : null;
	        });
	      }
	    });
	  });
	}

	main();

/***/ }
/******/ ]);