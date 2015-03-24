// dashboardController.js

	angular.module ('erich0929.blogApp.controller').
		controller ('dashboardController', ['$scope', 'BoardService', '$route', function ($scope, BoardService, $route) {

			// this function is depend on BoardService : getArticles (board)
			// warning : Asyncronize problem.
			$scope.getArticles = function (board) {
				var success = function (data) { 
					$scope.articles = data;
					$scope.initPages (1);
					$scope.allPage ($scope.pages);
					$scope.ranges = $scope.range ($scope.pages);
					// warning : updateCurrentPageArticles depends on above properties (allpage, ranges).
					$scope.updateCurrentPageArticles (1);
					
				};

				var boardService = new BoardService ();
				$scope.articles = boardService.getArticles (board, success);
			};

			$scope.updateCurrentPageArticles = function (page) {
				var pageArticles = [],
					start = (page - 1) * $scope.pages.view + 1,
					end = (start - 1) + $scope.pages.view;

					end = end > $scope.articles.length ? $scope.articles.length : end;

					for (var i = start; i <= end; i++) {
						pageArticles.push ($scope.articles [i-1]);
						//console.log ($scope.articles [i]);
					}
				$scope.pageArticles = pageArticles;

				// update pageObj
				$scope.pageObj = $scope.allPages [page - 1];
				//console.log ($scope.pageObj);
				//console.log ('update done, page : ' + page);
			};

			$scope.initPages = function (startPage) {
				//console.log ('initPages called.');
				$scope.pages = {
					start : startPage,
					range : 5,
					view : 10,
				};
				$scope.pages.totalPages = Math.ceil ($scope.articles.length / $scope.pages.view);
				//console.log ($scope.pages.totalPages);
			};

			$scope.allPage = function (pages) {
				$scope.allPages = [];
				for (var i = 0; i< pages.totalPages; i++) {
					var seq = (i + 1) + "/" + pages.totalPages;
					var element = { seq : seq, no : i + 1 };
					if (i == pages.start) $scope.pageObj = element;
					$scope.allPages.push (element);
				}

				return $scope.allPages;
			};

			$scope.updateStartPage = function (isPrev) {
				var start = $scope.pages.start;
				if (isPrev) {
					start -= $scope.pages.range;
				} else {
					start += $scope.pages.range;
				}
				$scope.pages.start = start > $scope.pages.totalPages ? $scope.pages.totalPages : start;
				
				$scope.allPage ($scope.pages);
				$scope.ranges = $scope.range ($scope.pages);
				console.log ($scope.pages);
				console.log ($scope.ranges);
				$scope.updateCurrentPageArticles (start);
			};

			$scope.updatePageObject = function (page) {
				$scope.updateCurrentPageArticles (page);
				$scope.pages.start = Math.floor ((page - 1) / $scope.pages.range) * $scope.pages.range + 1;
				$scope.ranges = $scope.range ($scope.pages);
			};

			$scope.range = function (pages) {
					var min = pages.start, 
						max, 
						end;

					end = pages.start + pages.range - 1;
					max = end > pages.totalPages ? pages.totalPages : end;

					var input = [];
					for (var i = min; i <= max; i++) input.push(i);
					//console.log ($scope.pages);
					//console.log ($scope.ranges);
					$scope.ranges = input;
					return input;
			};

			$scope.deleteArticle = function (boardName, articleId, index) {
				var boardService = new BoardService ();
				var callback = function (data) {
					var pageNo = $scope.pageObj.no;
					$scope.articles.splice (index, 1);
					$scope.initPages ($scope.pages.start);
					$scope.allPage ($scope.pages);
					$scope.ranges = $scope.range ($scope.pages);
					pageNo = pageNo > $scope.pages.totalPages ? $scope.pages.totalPages : pageNo; 
					if ($scope.pages.totalPages == 0) {
						$scope.ranges = null;
						$scope.allPages = null;
						$scope.pageObj = null;
						$scope.pageArticles = null;
						return;
					}
					$scope.updatePageObject (pageNo);
				};
				boardService.deleteArticle (boardName, articleId, callback);
			}

			$scope.pages = {
				start : 1,
				range : 5,
				view : 10,
				totalPages : 0
			};	
			
			
		}]);
