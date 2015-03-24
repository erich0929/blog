// app.js
	var app = angular.module ('erich0929.blogApp', ['ngRoute', 'erich0929.blogApp.controller',
													'erich0929.blogApp.service', 
													'erich0929.blogApp.directive', 'erich0929.blogApp.constant']),
		controller = angular.module ('erich0929.blogApp.controller', ['ngCookies', 'erich0929.blogApp.constant']),
		service = angular.module ('erich0929.blogApp.service', ['ngResource', 'ngCookies', 'erich0929.blogApp.constant']),
		directive = angular.module ('erich0929.blogApp.directive', ['erich0929.blogApp.constant']),
		constant = angular.module ('erich0929.blogApp.constant', [])
					.constant ('domain', 'http://localhost');
	app.config (['$routeProvider', function ($routeProvider) {
		$routeProvider
			.when (
				'/main', 
				{
					templateUrl : 'scripts/blog/templates/main.tmpl.html',
					controller : 'mainController',
					security : false,
					resolve : {
						mainArticles : function (BoardService, $route) {
							var boardService = new BoardService ();
							var boardName = $route.current.params.board || 'All';
							return boardService.getArticlesByPromise ({name : boardName}, null, 0, 5);
						}
					},
					title : 'main page'
				})
			.when (
				'/write',
				{
					templateUrl : 'scripts/blog/templates/write.tmpl.html',
					controller : 'writeController',
					security : 'admin',
					title : 'write page'
				})
			.when ('/edit/:boardName/:articleId', 
				{
					templateUrl : "scripts/blog/templates/edit.tmpl.html",
					controller : 'editController',
					security : 'admin',
					resolve : {
						article : function (BoardService, $route) {
							var boardService = new BoardService ();
							return boardService.getArticle ($route.current.params.boardName,
															$route.current.params.articleId);
						},
						boards : function (BoardService) {
							var boardService = new BoardService ();
							return boardService.getBoardsByPromise ();
						}
					}, 
					title : 'edit page'
				})
			.when ('/admin', 
				{
					templateUrl : 'scripts/blog/templates/admin.tmpl.html',
					controller : 'adminController',
					security : 'admin'
				})
			.when ('/admin/newtable', 
				{
					templateUrl : 'scripts/blog/templates/newtable.tmpl.html',
					controller : 'newtableController',
					resolve : {
						boards : function (BoardService) {
							var boardService = new BoardService ();
							return boardService.getBoardsByPromise (function (data) {return;});
						}
					},
					security : 'admin'
				})
			.when ('/admin/newarchive', 
				{
					templateUrl : 'scripts/blog/templates/newarchive.tmpl.html',
					controller : 'newarchiveController',
					security : 'admin'
				})
			.when ('/dashboard', 
				{
					templateUrl : 'scripts/blog/templates/dashboard.tmpl.html',
					controller : 'dashboardController',
					security : 'admin'
				})
			.when ('/dashboard/:board', 
				{
					templateUrl : 'scripts/blog/templates/dashboard.tmpl.html',
					controller : 'dashboardController',
					security : 'admin'

				})
			.when ('/view/:articleId/', 
				{
					templateUrl : 'scripts/blog/templates/view.tmpl.html',
					controller : 'viewController',
					security : false,
					resolve : {
						article : function (ArticleService, $route) {
							//console.log ($route.current.params);
							var articleService = new ArticleService ($route.current.params.articleId);
							return articleService.get ().$promise;
						}
					}
				})
			.when ('/login', 
				{
					templateUrl : 'scripts/blog/templates/login.tmpl.html',
					controller : 'loginController',
					security : false
				})
			.otherwise ({ redirectTo : '/main'});
	}]).run (['$rootScope', '$location', 'AuthService', function ($rootScope, $location, AuthService) {
		$rootScope.$on ('$routeChangeStart', function (event, next) {
			var authService = new AuthService ();
			authService.getUserinfo ().then (function (userinfo) {
				console.log ('[routeChangeStart] userName : ' + userinfo.userName);
				$rootScope.userName = userinfo.userName;
				// no authorized.
				if (!authService.authorize (next.security)) {
					$location.path ('/login');
				}
			});
			
			$rootScope.metadata = { 
			  		url : 'http://localhost/application/public/index.html#' + $location.path (), 
			  		description : ''
			};
				
		});
		$rootScope.$on('$routeChangeSuccess', function (e, current, pre) {	 
     			
    	});
	}]);

	app.controller ('appController', ['$scope','BoardService', function ($scope, BoardService) {
		$scope.brand = "Blog";
		var boardService = new BoardService ();
		$scope.boards = boardService.getBoards ();
	
	}]);
