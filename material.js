"use strict";
function preventNavigation() {
  var history_api = typeof history.pushState !== 'undefined';
  if (history_api) history.pushState(null, '', '#StayHere');
}
preventNavigation();

const app = angular
  .module('Wiki', [
    'ngResource',
    'ngMaterial'
  ])
  .config(function ($mdThemingProvider) {
    var darkTelekomMagenta = $mdThemingProvider.extendPalette('pink', {
      '50': '#ffecf6',
      '100': '#ffa0d1',
      '200': '#ff68b5',
      '300': '#ff2093',
      '400': '#ff0284',
      '500': '#e20074',
      '600': '#c30064',
      '700': '#a50055',
      '800': '#860045',
      '900': '#680035',
      'A100': '#ffecf6',
      'A200': '#ffa0d1',
      'A400': '#ff0284',
      'A700': '#a50055',
      'contrastDefaultColor': 'dark',
      'contrastDarkColors': '50 100 200 A100 A200',
      'contrastLightColors': '600 800 900 A400 A700'
    });
    $mdThemingProvider.definePalette('darkTelekomMagenta', darkTelekomMagenta)

    var darkTelekomGrey = $mdThemingProvider.extendPalette('grey', {
      '50': '#ababab',
      '100': '#848484',
      '200': '#686868',
      '300': '#454545',
      '400': '#353535',
      '500': '#262626',
      '600': '#171717',
      '700': '#070707',
      '800': '#000000',
      '900': '#000000',
      'A100': '#ffffff',
      'A200': '#848484',
      'A400': '#353535',
      'A700': '#070707',
      'contrastDefaultColor': 'dark',
      'contrastDarkColors': '50 100 A100 A200',
      'contrastLightColors': '600 800 900 A400 A700'
    });
    $mdThemingProvider.definePalette('darkTelekomGrey', darkTelekomGrey)

    var darkTelekomBlue = $mdThemingProvider.extendPalette('blue', {
      '50': '#e8f9ff',
      '100': '#9ce3ff',
      '200': '#64d4ff',
      '300': '#1cc0ff',
      '400': '#00b6fd',
      '500': '#00a0de',
      '600': '#008abf',
      '700': '#0074a1',
      '800': '#005e82',
      '900': '#004864',
      'A100': '#e8f9ff',
      'A200': '#9ce3ff',
      'A400': '#00b6fd',
      'A700': '#0074a1',
      'contrastDefaultColor': 'dark',
      'contrastDarkColors': '50 100 200 300 400 A100 A200 A400',
      'contrastLightColors': '600 800 900 A400 A700'
    });
    $mdThemingProvider.definePalette('darkTelekomBlue', darkTelekomBlue)

    var darkTelekomYellow = $mdThemingProvider.extendPalette('yellow', {
      '50': '#ffffff',
      '100': '#fff1bc',
      '200': '#ffe684',
      '300': '#ffd83c',
      '400': '#ffd21e',
      '500': '#fecb00',
      '600': '#dfb300',
      '700': '#c19a00',
      '800': '#a28200',
      '900': '#846900',
      'A100': '#ffffff',
      'A200': '#fff1bc',
      'A400': '#ffd21e',
      'A700': '#c19a00',
      'contrastDefaultColor': 'dark',
      'contrastDarkColors': '50 100 200 300 400 500 600 700 A100 A200 A400 A700',
      'contrastLightColors': '600 800 900 A400 A700'
    });
    $mdThemingProvider.definePalette('darkTelekomYellow', darkTelekomYellow)

    var darkTelekomRed = $mdThemingProvider.extendPalette('red', {
      '50': '#ffe3e3',
      '100': '#ff9797',
      '200': '#ff5f5f',
      '300': '#ff1717',
      '400': '#f80000',
      '500': '#d90000',
      '600': '#ba0000',
      '700': '#9c0000',
      '800': '#7d0000',
      '900': '#5f0000',
      'A100': '#ffe3e3',
      'A200': '#ff9797',
      'A400': '#f80000',
      'A700': '#9c0000',
      'contrastDefaultColor': 'dark',
      'contrastDarkColors': '50 100 200 A100 A200',
      'contrastLightColors': '600 800 900 A400 A700'
    });
    $mdThemingProvider.definePalette('darkTelekomRed', darkTelekomRed)

    var darkTelekomGreen = $mdThemingProvider.extendPalette('green', {
      '50': '#f0fae6',
      '100': '#caeda6',
      '200': '#ade478',
      '300': '#8ad83c',
      '400': '#7acc29',
      '500': '#6bb324',
      '600': '#5c991f',
      '700': '#4d801a',
      '800': '#3d6715',
      '900': '#2e4d10',
      'A100': '#f0fae6',
      'A200': '#caeda6',
      'A400': '#7acc29',
      'A700': '#4d801a',
      'contrastDefaultColor': 'dark',
      'contrastDarkColors': '50 100 200 300 400 500 A100 A200 A400',
      'contrastLightColors': '600 800 900 A400 A700'
    });
    $mdThemingProvider.definePalette('darkTelekomGreen', darkTelekomGreen);

    $mdThemingProvider.theme('default')
      .primaryPalette('darkTelekomMagenta')
      .accentPalette('darkTelekomBlue')
      .warnPalette('darkTelekomRed')
      .backgroundPalette('darkTelekomGrey');
  })
  /**
   * Put skin data (which usually processed by php) into an angular factory.
   */
  .factory('mwSkinData', [function () {
    let data = _mwSkinData
    delete _mwSkinData;
    return data;
  }])
  /**
   * Partial api.php service implementation with angular-resource.
   */
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
  /**
   * Random background factory from unsplash.com
   */
  .factory('unslashRandomBg', [
    '$http', '$interval',
    function ($http, $interval) {
      let interval = null;
      let background = null;
      let f = {};
      f.switchInterval = 30000;
      f.collection = false;
      Object.defineProperty(f, 'background', {
        get: function () {
          if (background === null) f.start();
          return background;
        }
      })
      f.stop = function () {
        if (interval) interval();
        interval = null;
        return f;
      }
      f.start = function () {
        f.stop();
        getRandomBgUrl(f.collection);
        interval = $interval(() => getRandomBgUrl(f.collection), f.switchInterval);
        return f;
      }
      function getRandomBgUrl(collection) {
        var url = (typeof collection == 'string') ? 'https://source.unsplash.com/collection/' + collection : 'https://source.unsplash.com/random'
        return $http.get(url, { responseType: 'arraybuffer' })
          .success(function (data, status, headers, config) {
            let blob = new Blob([data], { type: 'jpg' });
            let res = (window.URL || window.webkitURL).createObjectURL(blob);
            background = res;
          });
      }
      return f.start();
    }
  ])
  .controller('indexCtrl', [
    '$scope', '$mdSidenav', 'mwSkinData', 'mwApi', '$http',
    function ($scope, $mdSidenav, mwSkinData, mwApi, $http) {
      /**
       * Open or close sidenav panel
       */
      $scope.toggleSideNav = () => $mdSidenav('left').toggle();
      $scope.sitename = mwSkinData.sitename;
      /**
       * Extract header menu items from mwSkinData factory.
       */
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
      /**
       * Global anchor click handler.
       */
      $scope.clickHandler = function (item) {
        $scope.goToPage(item.href);
      }
      /**
       * Catch internal navigation events and replace them with a goToPage call
       */
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
      /**
       * Load content instead of article instead of reloading the whole page
       */
      let history = [window.location.pathname];
      $scope.goToPage = function (href) {
        if (typeof href == 'number') {
          if (history.length >= 0) return;
          preventNavigation();
          if (history.length > 1) history.pop();
          if (href < -1) $scope.goToPage(href+1);
          href = history.pop();
        }else {
          history.push(href);  
        }
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
      /**
       * Easter egg on empty searchText :)
       */
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
        /**
         * On enter pressed, if there is an exact match (not case sensitive) got to the page, if not got to fullTextSearch
         */
        onEnter: function (items) {
          if (items && items[0].text.toLowerCase() == $scope.search.searchText.toLowerCase()) {
            $scope.goToPage(items[0].href);
          } else {
            $scope.goToPage("/index.php?search=" + $scope.search.searchText + "&title=Special%3ASearch&fulltext=Search")
          }
        },
        /**
         * Go to selected page
         */
        selectedItemChange: function (item) {
          $scope.goToPage(item.href);
        },
        /**
         * Get matching pages from server while typing.
         */
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
        /**
         * Create new article (ex.: if there is no match)
         */
        createNew: function (searchText) {
          $scope.goToPage('/index.php?title=' + searchText + '&action=edit');
        }
      }
    /**
    * Init section
    */

      /**
       * Event-Listner for Back-Button
       */
      window.onhashchange = function (e) {
        $scope.goToPage(-1);
      };
      $(() => {
        /*
          * this swallows backspace keys on any non-input element.
          * stops backspace -> back
          */
        var rx = /INPUT|SELECT|TEXTAREA/i;
        $(document).bind("keydown keypress", function (e) {
          if (e.which == 8) { // 8 == backspace
            if (!rx.test(e.target.tagName) || e.target.disabled || e.target.readOnly) {
              e.preventDefault();
              $scope.goToPage(-1);
            }
          }
        });
      });

    }]
  )

angular.element(document).ready(function () {
  angular.bootstrap(document, [app.name], {
    strictDi: false //Some component (ex. mdDialog) fails if true (Cannot be minified)
  });
});
