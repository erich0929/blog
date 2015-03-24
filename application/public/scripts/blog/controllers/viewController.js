
// viewController.js

angular.module ('erich0929.blogApp.controller')
		.controller ('viewController', ['CommentService', 'domain', '$location', '$rootScope', '$scope', '$sce', 'article', function (CommentService, domain, $location, $rootScope, $scope, $sce, article) {
			
			$scope.postComment = function (message) {
				FB.login (function (response) {
					if(response.authResponse){
						FB.init({
  							appId      : '807361542684806',
  							xfbml      : true,
  							version    : 'v2.1',
  							cookie     : true
						});
 						console.log (response.authResponse.accessToken);
         				var commentService = new CommentService ();
         				commentService.postComments (article.boardName, 
         					article.articleId, message, 
         					response.authResponse.accessToken,
         					function (newComment) {
         						console.log ('[viewController] newComment : ' + newComment.userName);
         						$scope.message = '';
     							$scope.comments.push (newComment);
     							$scope.$apply();
         				});
         				
        			}
				}, {scope : 'email, publish_actions'});
			};

			$rootScope.metadata = { 
		  		url : domain + '/application/public/index.html#' + $location.path ().replace (/\/$/,''), 
		  		title : article.title,
		  		description : ''
			};	

			var content = article.content;
			var contentHtml = $sce.trustAsHtml (content);
			$scope.article = {
				title : article.title,
				date : article.date,
				author : article.author,
				content : contentHtml,
				articleId : article.articleId
			};

			var commentService = new CommentService ();
			commentService.getComments (article.boardName, article.articleId, 
			function (data) {
				$scope.comments = data;
				//console.log (data);
			});


		}]);