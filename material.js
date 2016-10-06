"use strict";
const app = angular.module('Wiki', [
  'ngMaterial'
])
.config(function($mdThemingProvider) {
  $mdThemingProvider.theme('default')
    .primaryPalette('pink')
    .accentPalette('grey');
})
.controller('indexCtrl', [
  '$scope', '$mdSidenav',
  function($scope, $mdSidenav){
    $scope.test = "Hello"
    $scope.toggleSideNav = () => $mdSidenav('left').toggle();
  }]
)

angular.element(document).ready(function () {
    angular.bootstrap(document, [app.name], {
        strictDi: false //Some component (ex. mdDialog) fails if true (Cannot be minified)
    });
});