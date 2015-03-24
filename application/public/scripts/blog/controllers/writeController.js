	angular.module ('erich0929.blogApp.controller')
			.controller ('writeController', ['$scope', 'BoardService', 'domain', function ($scope, BoardService, domain) {
				$scope.domain = domain;
				var boardSet = [];//$scope.boards;
				for (i=0;i<$scope.boards.length;i++) {
					//alert ($scope.boards[i].name);
					Array.prototype.push.apply (boardSet, [$scope.boards[i].name]);
				}
				$scope.boardSet = boardSet;
			}]);
