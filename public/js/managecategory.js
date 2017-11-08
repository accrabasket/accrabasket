function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};
var app = angular.module('app', []);
app.controller('managecategory', function ($scope, $http, $sce,$timeout) {
    $scope.errorShow = false;
    
    $scope.categoryData = {};
    $scope.savecategory = function (categoryData) {
		var error = ' ';
		if(categoryData.name == undefined || categoryData.name == ''){
			error = 'Please enter Category name' ;
		}
		if(categoryData.name == undefined || categoryData.name == ''){
			error = 'Please enter Category name' ;
		}      
                alert(error+'ss'+categoryData);
                console.log(categoryData);
                console.log(error);
		if(error == ' '){       
                        console.log(categoryData);
			$http({
				method: 'POST',
				data : ObjecttoParams(categoryData),
				url: serverAdminApp + '/dashboard/savecategory',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status) {
                                    var path = serverUrl + 'admin/dashboard/managecategory';
                                    window.location.href = path;
				}else{
                                    $scope.errorShow = true;
                                    $scope.errorMsg = response.msg == undefined ? 'somthing went wrong ':response.msg;
                                    $timeout(function(){
                                            $scope.errorShow = false;
                                    },200);
				}
			});
		}else{
			$scope.errorShow = true;
			$scope.errorMsg = error;
			$timeout(function(){
				$scope.errorShow = false;
			},200)
		}
		
    };
	
	
});	