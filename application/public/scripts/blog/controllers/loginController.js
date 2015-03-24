// loginController.js

	angular.module ('erich0929.blogApp.controller')
		.controller ('loginController', ['domain', '$scope', '$location', function (domain, $scope) {
			$scope.domain = domain;
			$scope.facebookLogin = function () {
				FB.login (function (response) {
					if(response.authResponse){
						FB.init({
  							appId      : '807361542684806',
  							xfbml      : true,
  							version    : 'v2.1',
  							cookie     : true
						});
 						console.log (response.authResponse.accessToken);
         				window.location.href = domain + "/index.php/facebookLogin?access="+response.authResponse.accessToken;
         				
        			}
				}, {scope : 'email, publish_actions'});
			}
			console.log ($scope.domain);	
		}]);