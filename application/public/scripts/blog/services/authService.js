// authService.js

	angular.module ('erich0929.blogApp.service')
		.factory ('AuthService', ['$resource', '$rootScope', 'domain', function ($resource, $rootScope, domain) {
			
			// constructor
			var authService = function () {

			};

			// public api
			authService.prototype = {
				authorize : function (securityFlag) {
					console.log ('[AuthService] : ' + $rootScope.userName);
					if (!securityFlag) return true;
					if (securityFlag == 'admin') {
						if ($rootScope.userName == 'admin') {
							
							return true;
						}
					}

					return false;
				},
				getUserinfo : function () {
					var url = domain + '/index.php/auth';
					console.log (url);
					var resource = $resource (url);
					return resource.get ().$promise;
				}
			};
	
			return authService;
		}]);