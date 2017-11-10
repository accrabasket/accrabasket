var app = angular.module('app', []);
app.controller('managelocation', function ($scope, $http, $sce,$timeout) {
    $scope.errorShow = false;
    
    $scope.locationData = {};
    $scope.locationData.country_id=0;
    $scope.locationData.active=1;
    function getCategory() {
        $http({
            method: 'POST',
            url: serverAdminApp + 'dashboard/getCategoryList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.categoryList = {};
            if(response.status == 'success'){
                $scope.categoryList = response.data;
            }
        });
    }
    
    getCategory();
    
    $scope.saveLocation = function (locationData) {
		var error = ' ';
		if(locationData.country_id == undefined || locationData.country_id == ''){
			error = 'Please select country.' ;
		}
		if(locationData.address == undefined || locationData.address == ''){
			error = 'Please enter address.' ;
		}                
		if(locationData.googlelocation == undefined || locationData.googlelocation == ''){
			error = 'Please choose location from google.' ;
		}                
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams(locationData),
				url: serverAdminApp + 'dashboard/savelocation',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status == 'success') {
                                        $scope.successShow = true;
                                        $scope.successMsg = response.msg ;
                                        $scope.successShow = false;
                                        var path = serverAdminApp + 'dashboard/location';
                                        window.location.href = path;
				}else{
                                    $scope.errorShow = true;
                                    $scope.errorMsg = response.msg == undefined ? 'somthing went wrong ':response.msg;
                                    $timeout(function(){
                                            $scope.errorShow = false;
                                    },2000);
				}
			});
		}else{
			$scope.errorShow = true;
			$scope.errorMsg = error;
			$timeout(function(){
				$scope.errorShow = false;
			},2000)
		}
		
    };
	
	
});	