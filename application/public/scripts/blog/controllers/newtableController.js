// newtableController.js

angular.module ('erich0929.blogApp.controller')
	.controller ('newtableController', ['$scope', 'BoardService', 'boards', function ($scope, BoardService, boards) {
		$scope.createTable = function (boardName, description) {
			var callback = function (response) {
				if (response.result) {
					$scope.boards.push ({name : boardName, description : description});
				}
				$scope.message = 'Failed to create table "' + boardName + '"';
				return;
			};
			var boardService = new BoardService ();
			boardService.createBoard (boarName, description, callback);
		}

		$scope.boards = boards;
		$scope.message = '';
	}]);