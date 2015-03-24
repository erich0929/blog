// commentService.js

angular.module ('erich0929.blogApp.service'/* ['ngResource', 'erich0929.blogApp.constant']*/)
.factory ('CommentService', ['$resource', 'domain', function ($resource, domain) {

	// private :


	// constructor
	var commentService = function () {

	};

	// public api

	commentService.prototype = {
		getComments : function (boardName, articleId, callback) {
			var url = domain + "/index.php/comments?boardName=" + boardName + "&articleId=" + articleId;
			console.log ('[CommentService] url : ' + url);
			var commentResource = $resource (url);
			var success = callback || function (data) {};
			return commentResource.query (success);
		},
		postComments : function (boardName, articleId, message, accessToken, callback) {
			var url = domain + "/index.php/comment";
			var commentResource = $resource (url);
			var postdata = 'boardName=' + boardName + '&articleId=' + articleId + '&message=' + message +
				'&sns=' + 'facebook' + '&accessToken=' + accessToken;
			var success = callback || function (data) {};
			$.ajax ({
                    url : url,
                    type : 'post',
                    data : postdata,
                    processData: false,
                    success : success
            });
		},
		postReply : function (commentId, message, accessToken) {

		}
	};
	return commentService;
}]);