"use strict";
const app = angular
  .module('Wiki', [
    'ngResource',
    'ngMaterial'
  ])
  .config(function($mdThemingProvider) {
    $mdThemingProvider.theme('default')
      .primaryPalette('pink')
      .accentPalette('grey');
  })
  .factory('mwSkinData', [function () {
    let data = _mwSkinData
    delete _mwSkinData;
    return data;
  }])
  .service('mwApi', [
    '$resource',
    function ($resource) {
      let baseUrl = '/api.php';
      let mwApi = $resource(baseUrl, {}, {
        opensearch: {
          method: 'GET',
          params: {
            action: 'opensearch',
            format: 'json',
            formatversion: 2,
            /*search: undefined,*/
            namespace: 0,
            profile: 'fuzzy', 
            limit: 10,
            suggest: true
          },
          isArray: true
        },
        query: {
          method: 'GET',
          params: {
            action: 'query',
            list: 'search',
            srprop: 'size|wordcount|timestamp|snippet',
            format: 'json',
            formatversion: 2,
            /*srsearch: undefined,*/ 
            srlimit: 10
          },
          isArray: true
        }
      })
      return mwApi;
    }
  ])
  .controller('indexCtrl', [
    '$scope', '$mdSidenav', 'mwSkinData', 'mwApi', '$http',
    function ($scope, $mdSidenav, mwSkinData, mwApi, $http) {
      $scope.toggleSideNav = () => $mdSidenav('left').toggle();
      console.log(mwSkinData);
      $scope.sitename = mwSkinData.sitename;
      $scope.headerMenus = ((d) => {
        let namespaceUrls = d.namespace_urls || {};
        let viewUrls = d.view_urls || {};
        let actionUrls = d.action_urls || {};
        let personalUrls = d.personal_urls || {};
        return [
          {
            text: 'Page',
            items: urlsToItems(namespaceUrls, viewUrls, actionUrls)
          },
          {
            text: 'User',
            items: urlsToItems(personalUrls)
          }
        ];
        function urlsToItems(urls) {
          if (arguments.length > 1) {
            let args = [...arguments];
            let result = [];
            args.forEach(arg => result = result.concat(urlsToItems(arg)))
            return result;
          }
          return Object.keys(urls).map(key => {
            return angular.extend({ name: key }, urls[key]);
          });
        }
      })(mwSkinData)
      $scope.sidebarGroups = mwSkinData.sidebar;
      $scope.clickHandler = function (item) {
        $scope.goToPage(item.href);
      }
      $scope.preventHref = function () {  
        let $internalLinks = $("a[href^='/'], a[href^='./'], a[href^='../']", "#content");
        $internalLinks.each((i, v) => {  
          let href = $(v).attr('href');
          $(v).removeAttr('href');
          $(v).click(() => {
            $scope.goToPage(href);
          });
        })
      }
      $scope.preventHref();
      $scope.goToPage = function (href) {
        $scope.search.isLoading = true;
        $http({
          url: href
        }).then((res) => {
          $('#content').html(
            $('#content', res.data).html()
          );
          $scope.preventHref();
          $scope.search.isLoading = false;
        })
      }
      $scope.randomQuote = function () {
        let quotes = [
          '1f u c4n r34d th1s u r34lly n33d t0 g37 l41d',
          'Do or Do Not. There is No Try...',
          "I'm not anti-social; I'm just not user friendly",
          "Bazinga!",
          "Wikipedia, I Am Your Father",
          "Watch Star Trek instead...",
          "Don't blink",
          "Punch it, Chewie!",
          "May the force be with you...",
          "Winter is coming...",
          "Geek is the new Sexy",
          "Talk nerdy to me.",
          "Who ya gonna call?",
          "Run you fools",
          "Say after me: Accio <name-of-article>!",
          "Say what again. SAY WHAT again! And I dare you, I double dare you motherfucker...",
          "Magic Mirror on the wall, who is the fairest one of all?",
          "Hasta la vista, baby.",
          "I'll be back!",
          "Just keep swimming.",
          "Shaken, not stirred.",
          "Roads? Where we're going we don't need roads.",
          "Houston, we have a problem.",
          "To infinity and beyond!",
          "Yippie-ki-yay, motherf—er!",
          "E.T. phone home.",
          "I see dead people.",
          "This is the beginning of a beautiful friendship.",
          "Why so serious?",
          "The first rule of Fight Club is: You do not talk about Fight Club.",
          "Toto, I've a feeling we're not in Kansas anymore.",
          "When you play the Game of Thrones, you win or you die.",
          "The Lannisters send their regards.",
          "You know nothing, Jon Snow.",
          "Go home, your watch has ended."
        ]
        return quotes[parseInt(quotes.length * Math.random())]
      }
      $scope.search = {
        isDisabled: false,
        noCache: true,
        searchText: undefined,
        onEnter: function (items) {
            if (items && items[0].text.toLowerCase() == $scope.search.searchText.toLowerCase()) {
              $scope.goToPage(items[0].href);
            } else {
              $scope.goToPage("/index.php?search=" + $scope.search.searchText + "&title=Special%3ASearch&fulltext=Search")
            }
        },
        selectedItemChange: function (item) {
          $scope.goToPage(item.href);
        },
        querySearch: function (searchText) {
          return new Promise(function (resolve, reject) {
            if (searchText == false) return resolve([]);
            let req = mwApi.opensearch({
              search: searchText
            })
            if (req.$promise) 
              req.$promise.then(
                (res) => {
                  let result = [];
                  let i = -1;
                  while (res[1][++i]) {
                    result.push({
                      text: res[1][i],
                      href: res[3][i]
                    })
                  }
                  $scope.search.lastResult = result;
                  resolve(result);
                },
                (err) => {
                  console.warn(err);
                  reject(err);
                }
              );
          })
        },
        createNew: function (searchText) {
          $scope.goToPage('/index.php?title=' + searchText + '&action=edit');
        }
      }
    }]
  )

angular.element(document).ready(function () {
    angular.bootstrap(document, [app.name], {
        strictDi: false //Some component (ex. mdDialog) fails if true (Cannot be minified)
    });
});
