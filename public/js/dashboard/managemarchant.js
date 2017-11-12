var app = angular.module('marchantapp', []);
app.controller('marchantController', function ($scope, $http, $sce,$timeout, marchantList) {
    $scope.errorShow = false;
    $scope.marchantData = {};
    if(marchantList != ''){
        var marchantList = jQuery.parseJSON(marchantList);
        console.log(marchantList);
        $scope.marchantData = marchantList;
        $scope.marchantData.name = marchantList.first_name;
    }
    $scope.savemerchant = function () {
		var error = ' ';	                
                if($scope.marchantData.bank_name == undefined || $scope.marchantData.bank_name == ''){
			error = 'Bank name can not be empty. ' ;
		}
                if($scope.marchantData.address == undefined || $scope.marchantData.address == ''){
			error = 'Please enter address ' ;
		}
                if($scope.marchantData.email_id == undefined || $scope.marchantData.email_id == ''){
			error = 'Please enter valid email' ;
		}
		if($scope.marchantData.phone_number == undefined || $scope.marchantData.phone_number == ''){
			error = 'Please enter phone number' ;
		}
		if($scope.marchantData.ic_number == undefined || $scope.marchantData.ic_number == ''){
			error = 'Please enter ic number' ;
		}                
		if($scope.marchantData.name == undefined || $scope.marchantData.name == ''){
			error = 'Please enter owner name' ;
		}                
                
                
		if(error == ' '){
			$http({
				method: 'POST',
				data : ObjecttoParams(marchantData),
				url: serverAppUrl + '/updatemerchant',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status) {
					$scope.successShow = true;
			                $scope.successMsg = response.msg
                                        $timeout(function(){
                                                $scope.successShow = false;
                                                var path = serverAdminApp + 'dashboard/managemerchant';
                                                window.location.href = path;
                                        },1000)
				}else{
					$scope.errorShow = true;
					$scope.errorMsg = response.msg == undefined ? 'somthing went wrong ':response.msg;
                                        $timeout(function(){
                                                $scope.errorShow = false;
                                        },2000)
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