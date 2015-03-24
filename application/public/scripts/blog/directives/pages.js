// pages.js

angular.module ('erich0929.blogApp.directive', []).
	directive ('pages', function ($log) {
		return {
			templateUrl : 'scripts/blog/templates/pages.tmpl.html',
			restrict : 'A',
			scope : { 
				pages : "=obj" ,
				updatePage : "&"
			}, 
			controller : function ($scope, $element, $attrs, $transclude) {
				$scope.range = function(pages){
					var min = pages.start, 
						max, 
						end;

					end = pages.start + pages.range - 1;
					max = end > pages.totalPages ? pages.totalPages : end;

					var input = [];
					for (var i = min; i <= max; i++) input.push(i);
					return input;
				};

			}
		};

	});