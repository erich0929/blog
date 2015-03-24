// editController.js

	angular.module ('erich0929.blogApp.controller')
		.controller ('editController', ['$scope', 'article', 'boards', function ($scope, article, boards) {

			$scope.article = article;
			$scope.boards = boards;

			//ng-option default value.
			for (var i = 0 ; i < boards.length ; i++) {
				if (boards [i].name == article.boardName) {
					$scope.board = boards [i]; 
					break;
				} 
			}

		}]);