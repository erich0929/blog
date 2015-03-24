// newtableController.js

angular.module ('erich0929.blogApp.controller')
	.controller ('newtableController', ['$scope', 'BoardService', 'boards', function ($scope, BoardService, boards) {
		$scope.createTable = function (boardName, description) {
			var callback = function (response) {
				console.log ('create table log');
				if (response.result) {
					$scope.boards.push ({name : boardName, description : description});
					return;
				}
				$scope.message = 'Failed to create table "' + boardName + '"';
				return;
			};
			var boardService = new BoardService ();
			boardService.createBoard (boardName, description, callback);
		}

		$scope.deleteTable = function (boardName, spliceIndex) {
			if (!confirm ('Are you sure drop ' + boardName + ' table?')) return;
			var callback = function (response) {
				if (response.result) {
					$scope.boards.splice (spliceIndex, 1);
					return;
				}
				$scope.message = 'Failed to delete table "' + boardName + '"';
				return;
			};
			var boardService = new BoardService ();
			boardService.deleteBoard (boardName, callback);
		};

		$scope.boards = boards;
		$scope.message = '';
	}]);