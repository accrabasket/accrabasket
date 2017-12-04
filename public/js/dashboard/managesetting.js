var app = angular.module('app', []);
app.controller('managesetting', function ($scope, $http, $sce,$timeout, settingList) {
    $scope.errorShow = false;
    
    $scope.settingData = {};
    $scope.id = '';
    
    if(settingList != ''){
        var settingList = jQuery.parseJSON(settingList);
        $scope.settingData = settingList;
        $scope.id = settingList.id;
    }
    
    $scope.savesetting = function (settingData) {
		var error = ' ';
		if($scope.id != ''){
			$scope.settingData.id = $scope.id;
		}      
                
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams(settingData),
				url: serverAdminApp + 'dashboard/savesetting',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status == 'success') {
                                        $scope.successShow = true;
                                        $scope.successMsg = response.msg ;
                                        $timeout(function(){
                                                $scope.successShow = false;
                                        },2000);
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