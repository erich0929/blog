// mainController.js

	angular.module ('erich0929.blogApp.controller')
			.controller ('mainController', ['$scope', '$sce', 'mainArticles', 'BoardService', '$route', function ($scope, $sce, mainArticles, BoardService, $route) {

				var boardService = new BoardService ();
				var limitArticles = 5;
				// main articlesContainer
				var articlesContainer = [];
				var idMapToPage = [];

				// initialize.
				if (mainArticles.length == 0) return;
				articlesContainer [1] = mainArticles;

				idMapToPage [mainArticles [0].articleId] = 1;
				idMapToPage [mainArticles [mainArticles.length -1].articleId] = 1;


				var boardName = $route.current.params.board || "All";

				$scope.getSummary = function (articles) {
					var imageTagRegex = /<img.*?>/i; //using lazy match.
					var htmlTag = /<.*?>/gm;
					var contentLimit = 370;
					
					
					for (var i=0; i < articles.length; i++) {
						var emptyString = /\s+/gi;
						emptyString.lastIndex = contentLimit - 10;

						var article = articles [i];
						if (article.trusted) continue;

						article.image = article.content.match (imageTagRegex);
						//remove image tag.
						article.content = article.content.replace (imageTagRegex, '');
						//summary content.
						article.content = article.content.replace (htmlTag, '');
						//empty tag index
						var emptyMatch = emptyString.exec (article.content);
						
						var lastIndex = contentLimit;
						if (emptyMatch) {
							var index = emptyMatch ['index'];
							lastIndex = index + emptyMatch [0].length;
						}
						lastIndex = lastIndex > contentLimit + 30 ? contentLimit : lastIndex;
						
						article.content = $sce.trustAsHtml (article.content.substring (0, lastIndex) + ' <a href="#/view/' + article.articleId + '" target="_self">... more</a>');
						article.trusted = true;
					}

					return articles;
				};
				
				$scope.mainArticles = $scope.getSummary (mainArticles);


				$scope.getNextPage = function (mainArticles) {
					var lastArticleId = mainArticles [mainArticles.length -1].articleId;
					//console.log (mainArticles);
					var currentPage = idMapToPage [lastArticleId];
					console.log ("next call : " + currentPage);
					//var getSummary = $scope.getSummary;

					var callback = function (data) {
						// boundary value problem.
						if (data.length == 0) {
							data = mainArticles;
							return;
						}
						//console.log (data);
						var nextPage = currentPage + 1;
						console.log ("next call ; nextPage : " + nextPage);
						// id map to page.
						idMapToPage [data [data.length - 1].articleId] = nextPage; 
						idMapToPage [data [0].articleId] = nextPage;
						// store in a cache.
						//console.log (articlesContainer);
						articlesContainer [nextPage] = $scope.getSummary (data);
						$scope.mainArticles = articlesContainer [nextPage];
						return;
					};

					getArticles (boardName, callback, currentPage + 1, lastArticleId);
					return;
				};

				$scope.getPrevPage = function (mainArticles) {
					var frontArticleId = mainArticles [0].articleId;
					var currentPage = idMapToPage [frontArticleId];
					console.log ("prev currentPage : " + currentPage);
					if (currentPage == 1) currentPage++;

					var callback = null;/*function (mainArticles) {
						// boundary value problem.
						$scope.mainArticles = mainArticles.length || $scope.mainArticles;
						var prevPage = (mainArticles) ? currentPage - 1: currentPage;
						// id map to page.
						idMapToPage [mainArticles [mainArticles.length - 1].articleId] = prevPage; 
						idMapToPage [mainArticles [0].articleId] = prevPage;
						// store in a cache.
						articlesContainer [prevPage] = $scope.getSummary ($scope.mainArticles);
						return;
					};*/

					getArticles (boardName, callback, currentPage - 1, frontArticleId);
					return;
				};

				function getArticles (boardName, callback, page, id) {
					// find articles in a cache.
					if (articlesContainer [page]) {
						console.log ("let's cache.");
						$scope.mainArticles = articlesContainer [page];
						return;
					}
					// if not , request articles.
					return boardService.getArticles ({name : boardName}, callback, id, limitArticles);
				}

				

			}]);