// boardService.js

angular.module ('erich0929.blogApp.service'/* ['ngResource', 'erich0929.blogApp.constant']*/)
.factory ('BoardService', ['$resource', 'domain', function ($resource, domain) {
	//var url = domain + '/index.php/boards';
	//var boardResource = $resource (url, {},{ query : {method : 'GET', isArray : true}});
	//return boardResource;

	//static private :
	var url = domain + '/index.php/boards';

	function getArticlesResource (board, id, limit) {
		
		var url = domain + '/index.php/boards/' + board.name;
		var idParam = 'id=' + id;
		var limitParam = 'limit=' + limit;
		if (id) {
			url += '?' + idParam;
			if (limit) url += '&' + limitParam;
		} else {
			if (limit) url += '?' + limitParam;
		}
		console.log (url);
		var boardResource = $resource (url);
		return boardResource;
	}

	// constructor
	var boardService = function () {

		//private :
		//var url = url;
		//public :


	};

	// public method
	boardService.prototype = {

		getBoards : function () {
			var boardResource = $resource (url);
			return boardResource.query (function (data) {
				data.unshift ({name : 'All', description : '*'});
			});
		},

		getBoardsByPromise : function (callback) {
			var success = callback || 
				function (data) {
					data.unshift ({name : 'All', description : '*'});
				};
			var boardResource = $resource (url);
			return boardResource.query (success).$promise;
		},

		getArticle : function (boardName, articleId, callback) {
			var success = callback || function (data) {};
			var url = domain + '/index.php/boards/' + boardName + '/' + articleId;
			console.log (url);
			var boardResource = $resource (url);
			return boardResource.get (success).$promise;
		},

		getArticles : function (board, callback, id, limit) {
			var boardResource = getArticlesResource (board, id, limit);
			var success = callback || function (data) {};
			return boardResource.query (success);
		},

		getArticlesByPromise : function (board, callback, id, limit) {
			var boardResource = getArticlesResource (board, id, limit);
			var success = callback || function (data) {};
			return boardResource.query (success).$promise;
		},

		deleteArticle : function (boardName, articleId, callback) {
			var success = callback || function (data) {};
			var url = domain + "/index.php/delete/" + boardName + "/" + articleId;
			var boardResource = $resource (url);
			return boardResource.get (success);
		}, 
		createBoard : function (boardName, description, callback) {
			var success = callback || function (data) {};
			var url = domain + "/index.php/newtable";
			var data = "boardName=" + boardName + "&description=" + description;
			var newtableResource = $resource (url, {}, 
				{ 
					save : {
						method : 'POST', 
						headers : { 'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
					}
				});
			return newtableResource.save (data).$promise.then (success);
		},
		deleteBoard : function (boardName, callback) {
			var success = callback || function (data) {};
			var url = domain + "/index.php/droptable";
			var data = "boardName=" + boardName;
			var droptableResource = $resource (url, {}, 
				{ 
					delete : {
						method : 'POST', 
						headers : { 'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
					}
				});
			return droptableResource.delete (data).$promise.then (success);
		}
	};

	return boardService;
}]);

	